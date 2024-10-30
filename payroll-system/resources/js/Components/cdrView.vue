<template>
  <div class="relative">
    <!-- Back Button - Only show when viewing beneficiaries -->
    <button
      v-if="selectedCdr"
      @click="goBackToCdrList"
      class="mb-4 flex items-center text-blue-600 hover:text-blue-800"
    >
      <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M15 19l-7-7 7-7"
        />
      </svg>
      Back to CDR List
    </button>

    <!-- Main Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">CDR Management</h1>
      <!-- Only show search when viewing CDR list -->
      <div v-if="!selectedCdr" class="w-[32%]">
        <label for="search" class="sr-only">Search CDRs</label>
        <div class="relative">
          <input
            id="search"
            v-model="search"
            @input="debouncedSearch"
            type="text"
            placeholder="Search by CDR Name, CDR Number, or Payroll"
            class="bg-white text-gray-700 rounded-full pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full h-9"
          />
          <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
            <svg
              class="w-5 h-5 text-gray-400"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Error Alert -->
    <div
      v-if="beneficiaryError"
      class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative"
    >
      <span class="block sm:inline">{{ beneficiaryError }}</span>
      <button
        @click="retryFetchBeneficiaries"
        class="ml-4 text-red-700 hover:text-red-900 underline"
      >
        Retry
      </button>
    </div>

    <!-- CDR Table - Only show when no CDR is selected -->
    <div
      v-if="!selectedCdr && !isLoading"
      class="bg-white shadow overflow-hidden sm:rounded-lg mb-6"
    >
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              v-for="header in headers"
              :key="header.key"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              :class="{ 'cursor-pointer': header.sortable }"
              @click="header.sortable && sort(header.key)"
            >
              {{ header.label }}
              <span v-if="header.sortable" class="ml-1">
                {{
                  sortField === header.key ? (sortDirection === "asc" ? "↑" : "↓") : "↕"
                }}
              </span>
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="cdr in cdrs.data"
            :key="cdr.cdrID"
            @click="viewBeneficiaries(cdr)"
            class="cursor-pointer hover:bg-gray-50 transition-colors duration-150"
          >
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ cdr.cdrID }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ cdr.payroll?.payrollNumber }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ cdr.cdrName }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(cdr.created_at) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add loading spinner component -->
    <div
      v-if="loadingStates.table || loadingStates.beneficiaries"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white p-4 rounded-lg shadow-lg">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        <p class="mt-2 text-gray-600">Loading...</p>
      </div>
    </div>

    <!-- Error alerts -->
    <div
      v-for="(error, key) in errorStates"
      v-if="error"
      :key="key"
      class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative"
    >
      <span class="block sm:inline">{{ error }}</span>
      <button
        @click="retryOperation(key)"
        class="ml-4 text-red-700 hover:text-red-900 underline"
      >
        Retry
      </button>
    </div>

    <!-- Field validation errors -->
    <div
      v-for="(error, field) in validationErrors"
      v-if="touchedFields.has(field) && error"
      :key="field"
      class="text-red-600 text-sm mt-1"
    >
      {{ error }}
    </div>

    <!-- Optimistic updates -->
    <div
      v-for="[id, update] in pendingUpdates"
      :key="id"
      class="fixed bottom-4 right-4 p-4 rounded-lg shadow-lg"
      :class="{
        'bg-yellow-100': update.status === 'pending',
        'bg-green-100': update.status === 'success',
        'bg-red-100': update.status === 'failed',
      }"
    >
      {{
        update.status === "pending"
          ? "Saving..."
          : update.status === "success"
          ? "Saved successfully"
          : "Failed to save"
      }}
    </div>

    <!-- Success/Error Alert -->
    <div
      v-if="alert.show"
      :class="[
        'mt-4 p-4 rounded-md',
        alert.type === 'success'
          ? 'bg-green-100 text-green-700'
          : 'bg-red-100 text-red-700',
      ]"
    >
      {{ alert.message }}
    </div>

    <!-- Beneficiaries Section - Show when a CDR is selected -->
    <div v-if="selectedCdr" class="mt-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">
          Beneficiaries for CDR {{ selectedCdr.cdrID }}
          <span v-if="selectedCdr.payroll" class="text-sm text-gray-500">
            (Payroll: {{ selectedCdr.payroll.payrollNumber }})
          </span>
        </h2>
        <span v-if="beneficiaries.length" class="text-sm text-gray-500">
          Showing {{ beneficiariesPagination.from }}-{{ beneficiariesPagination.to }} of
          {{ beneficiariesPagination.total }} beneficiaries
        </span>
      </div>

      <!-- Dropdown Row -->
      <div class="grid grid-cols-4 gap-4 mb-6">
        <!-- Entity Name - Fund Cluster -->
        <div class="relative">
          <label
            for="entityNameFundCluster"
            class="block text-sm font-medium text-gray-700 mb-1"
            >Entity Name - Fund Cluster</label
          >
          <select
            id="entityNameFundCluster"
            v-model="selectedFilters.entityNameFundCluster"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-full"
            :disabled="!selectedCdr"
          >
            <option value="">Select Entity Name - Fund Cluster</option>
            <option
              v-for="entity in dropdownOptions.entities"
              :key="entity.entityID"
              :value="entity.entityID"
              class="p-3 rounded-full"
            >
              {{ entity.entityName }} - {{ entity.fundCluster }}
            </option>
          </select>
        </div>

        <!-- Officer - Designation - Station -->
        <div class="relative">
          <label
            for="officerDesignationStation"
            class="block text-sm font-medium text-gray-700 mb-1"
            >Officer - Designation - Station</label
          >
          <select
            id="officerDesignationStation"
            v-model="selectedFilters.officerDesignationStation"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-full"
            :disabled="!selectedCdr"
          >
            <option value="">Select Officer - Designation - Station</option>
            <option
              v-for="designation in dropdownOptions.designations"
              :key="designation.designationID"
              :value="designation.designationID"
              class="p-3 rounded-full"
            >
              {{ designation.accountableOfficer }} -
              {{ designation.officialDesignation }} -
              {{ designation.station }}
            </option>
          </select>
        </div>

        <!-- Reference Number -->
        <div class="relative">
          <label
            for="referenceNumber"
            class="block text-sm font-medium text-gray-700 mb-1"
            >Reference Number</label
          >
          <select
            id="referenceNumber"
            v-model="selectedFilters.referenceNumber"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-full"
            :disabled="!selectedCdr"
          >
            <option value="">Select Reference Number</option>
            <option
              v-for="ref in dropdownOptions.dvPayrolls"
              :key="ref.dvPNumber"
              :value="ref.dvPNumber"
              class="p-3 rounded-full"
            >
              {{ ref.dvPNumber }} - {{ ref.check_no }}
            </option>
          </select>
        </div>

        <!-- UACS Object Code -->
        <div class="relative">
          <label for="uacsCode" class="block text-sm font-medium text-gray-700 mb-1"
            >UACS Object Code</label
          >
          <select
            id="uacsCode"
            v-model="selectedFilters.uacsCode"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-full"
            :disabled="!selectedCdr"
          >
            <option value="">Select UACS Code</option>
            <option
              v-for="code in dropdownOptions.uacsCodes"
              :key="code.uacsObjectCode"
              :value="code.uacsObjectCode"
              class="p-3 rounded-full"
            >
              {{ code.uacsObjectCode }}
            </option>
          </select>
        </div>

        <!-- Nature of Payment -->
        <div class="relative">
          <label
            for="natureOfPayment"
            class="block text-sm font-medium text-gray-700 mb-1"
            >Nature of Payment</label
          >
          <select
            id="natureOfPayment"
            v-model="selectedFilters.natureOfPayment"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-full"
            :disabled="!selectedCdr"
          >
            <option value="">Select Nature of Payment</option>
            <option
              v-for="nature in dropdownOptions.paymentsNature"
              :key="nature.nOPId"
              :value="nature.nOPId"
              class="p-3 rounded-full"
            >
              {{ nature.natureOfPayment }}
            </option>
          </select>
        </div>
      </div>

      <!-- Insert Data Button -->
      <div class="flex justify-end m-4">
        <button
          @click="insertData"
          class="px-4 py-2 bg-blue-900 text-white rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="!isFormValid || isLoading"
        >
          <span v-if="isLoading">Processing...</span>
          <span v-else>Insert Data</span>
        </button>
      </div>

      <!-- Add New Button -->
      <div class="flex justify-end m-4">
        <button
          @click="openAddNewModal"
          class="px-4 py-2 bg-blue-900 text-white rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Add New Data
        </button>
      </div>

      <!-- Beneficiaries Loading State -->
      <div v-if="isLoadingBeneficiaries" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <!-- Beneficiaries Table -->
      <div
        v-else-if="beneficiaries.length"
        class="bg-white shadow overflow-hidden sm:rounded-lg"
      >
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                v-for="header in beneficiaryHeaders"
                :key="header.key"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                {{ header.label }}
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="beneficiary in beneficiaries"
              :key="beneficiary.id"
              :class="{
                'bg-red-50': beneficiary.status === 'Inactive',
              }"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ beneficiary.payrollNumber }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ beneficiary.lastName }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ beneficiary.firstName }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ beneficiary.middleName || "-" }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ beneficiary.extensionName || "-" }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <span
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-800': beneficiary.status === 'Active',
                    'bg-red-100 text-red-800': beneficiary.status === 'Inactive',
                    'bg-gray-100 text-gray-800': beneficiary.status === 'Unknown',
                  }"
                >
                  {{ beneficiary.status }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Beneficiaries Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <button
              @click="changeBeneficiariesPage(beneficiariesPagination.current_page - 1)"
              :disabled="beneficiariesPagination.current_page === 1"
              class="px-4 py-2 border rounded text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
            >
              Previous
            </button>
            <span class="text-sm text-gray-700">
              Page {{ beneficiariesPagination.current_page }} of
              {{ beneficiariesPagination.last_page }}
            </span>
            <button
              @click="changeBeneficiariesPage(beneficiariesPagination.current_page + 1)"
              :disabled="
                beneficiariesPagination.current_page === beneficiariesPagination.last_page
              "
              class="px-4 py-2 border rounded text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
            >
              Next
            </button>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-12 text-gray-500">
        No beneficiaries found for this CDR.
      </div>
    </div>

    <!-- Main Loading State -->
    <div v-if="isLoading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- CDR Pagination - Only show when viewing CDR list -->
    <div v-if="!selectedCdr && cdrs.links && cdrs.links.length > 3" class="mt-6">
      <pagination :links="cdrs.links" />
    </div>

    <!-- Add New Modal -->
    <TransitionRoot appear :show="isModalOpen" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-50">
        <div class="fixed inset-0 bg-black bg-opacity-25" />

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4">
            <DialogPanel
              class="w-full max-w-md transform overflow-hidden rounded-lg bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                Add New Item
              </DialogTitle>

              <!-- Update the modal form section -->
              <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700"
                  >Select Category</label
                >
                <select
                  v-model="selectedCategory"
                  class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                >
                  <option value="">Select a category</option>
                  <option value="entityNameFundCluster">
                    Entity Name - Fund Cluster
                  </option>
                  <option value="officerDesignationStation">
                    Officer - Designation - Station
                  </option>
                  <option value="referenceNumber">Reference Number</option>
                  <option value="uacsCode">UACS Object Code</option>
                  <option value="natureOfPayment">Nature of Payment</option>
                </select>

                <!-- Dynamic form fields based on category -->
                <div v-if="selectedCategory" class="mt-4 space-y-4">
                  <!-- Entity Name - Fund Cluster -->
                  <template v-if="selectedCategory === 'entityNameFundCluster'">
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Entity Name</label
                      >
                      <input
                        type="text"
                        v-model="formData.entityName"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        required
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Fund Cluster</label
                      >
                      <input
                        type="text"
                        v-model="formData.fundCluster"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        required
                      />
                    </div>
                  </template>

                  <!-- Officer - Designation - Station -->
                  <template v-if="selectedCategory === 'officerDesignationStation'">
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Accountable Officer</label
                      >
                      <input
                        type="text"
                        v-model="formData.accountableOfficer"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        required
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Official Designation</label
                      >
                      <input
                        type="text"
                        v-model="formData.officialDesignation"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        required
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Station</label
                      >
                      <input
                        type="text"
                        v-model="formData.station"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        required
                      />
                    </div>
                  </template>

                  <!-- Reference Number -->
                  <template v-if="selectedCategory === 'referenceNumber'">
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >ADA/DV/Payroll/Reference Number</label
                      >
                      <input
                        type="text"
                        v-model="formData.dvPNumber"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        required
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Check Number</label
                      >
                      <input
                        type="number"
                        v-model="formData.checkNo"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        required
                      />
                    </div>
                  </template>

                  <!-- UACS Object Code -->
                  <template v-if="selectedCategory === 'uacsCode'">
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >UACS Object Code</label
                      >
                      <input
                        type="text"
                        v-model="formData.uacsObjectCode"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        required
                      />
                    </div>
                  </template>

                  <!-- Nature of Payment -->
                  <template v-if="selectedCategory === 'natureOfPayment'">
                    <div>
                      <label class="block text-sm font-medium text-gray-700"
                        >Nature of Payment</label
                      >
                      <input
                        type="text"
                        v-model="formData.natureOfPayment"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                        required
                      />
                    </div>
                  </template>
                </div>
              </div>

              <div class="mt-6 flex justify-end space-x-3">
                <button
                  @click="closeModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                  Cancel
                </button>
                <button
                  @click="saveNewItem"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Save
                </button>
              </div>
            </DialogPanel>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!--main div-->
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import axios from "axios";
import { Dialog, DialogPanel, DialogTitle, TransitionRoot } from "@headlessui/vue";

const props = defineProps({
  cdrs: {
    type: Object,
    required: true,
  },
  error: {
    type: String,
    default: "",
  },
});

// New state for dropdowns and modal
const dropdownOptions = ref({
  entities: [],
  designations: [],
  dvPayrolls: [],
  uacsCodes: [],
  paymentsNature: [],
});

const formData = ref({
  entityName: "",
  fundCluster: "",
  accountableOfficer: "",
  officialDesignation: "",
  station: "",
  dvPNumber: "",
  checkNo: "",
  uacsObjectCode: "",
  natureOfPayment: "",
});

const selectedFilters = ref({
  entityNameFundCluster: "",
  officerDesignationStation: "",
  referenceNumber: "",
  uacsCode: "",
  natureOfPayment: "",
});

// Loading states for different operations
const loadingStates = ref({
  table: false,
  beneficiaries: false,
  dropdown: false,
  insert: false,
  modal: false,
});

// Error states for different operations
const errorStates = ref({
  table: null,
  beneficiaries: null,
  dropdown: null,
  insert: null,
  modal: null,
});

// Table headers
const headers = [
  { key: "cdrID", label: "CDR ID", sortable: true },
  { key: "payrollNumber", label: "Payroll Number", sortable: true },
  { key: "cdrName", label: "CDR Name", sortable: true },
  { key: "created_at", label: "Created At", sortable: true },
];

const beneficiaryHeaders = [
  { key: "payrollNumber", label: "Payroll Number" },
  { key: "lastName", label: "Last Name" },
  { key: "firstName", label: "First Name" },
  { key: "middleName", label: "Middle Name" },
  { key: "extensionName", label: "Name Extension" },
  { key: "status", label: "Status" },
];
// State
const search = ref("");
const isLoading = ref(false);
const sortField = ref("cdrID");
const sortDirection = ref("asc");
const selectedCdr = ref(null);
const beneficiaries = ref([]);
const isLoadingBeneficiaries = ref(false);
const beneficiaryError = ref("");
const isModalOpen = ref(false);
const selectedCategory = ref("");
const alert = ref({ show: false, message: "", type: "success" });

// Enhanced form validation
const validationErrors = ref({});
const touchedFields = ref(new Set());

// Optimistic updates
const pendingUpdates = ref(new Map());

// Retry counters
const retryAttempts = ref({
  beneficiaries: 0,
  dropdown: 0,
});

const MAX_RETRY_ATTEMPTS = 3;

const beneficiariesPagination = ref({
  current_page: 1,
  last_page: 1,
  from: 0,
  to: 0,
  total: 0,
  per_page: 12,
});

// Setup axios interceptors for CSRF
const setupAxiosInterceptors = () => {
  axios.interceptors.request.use((config) => {
    // Add CSRF token from meta tag
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
      config.headers["X-CSRF-TOKEN"] = token.content;
    }
    return config;
  });

  // Add response interceptor for error handling
  axios.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response?.status === 419) {
        // CSRF token mismatch - refresh the page
        window.location.reload();
      }
      return Promise.reject(error);
    }
  );
};

// Form handling
const form = useForm({
  search: "",
  sort_field: sortField.value,
  sort_direction: sortDirection.value,
  filters: {},
});

// Improved performSearch method
const performSearch = () => {
  isLoading.value = true;
  const formData = {
    search: form.search,
    sort_field: form.sort_field,
    sort_direction: form.sort_direction,
    filters: { ...selectedFilters.value },
  };

  form.get(route("cdr.index"), {
    data: formData,
    preserveState: true,
    preserveScroll: true,
    only: ["cdrs"],
    onSuccess: () => {
      isLoading.value = false;
    },
    onError: () => {
      isLoading.value = false;
    },
  });
};

const fetchBeneficiaries = async (payrollId, page = 1, isRetry = false) => {
  if (!payrollId) {
    errorStates.value.beneficiaries = "Invalid payroll ID";
    return;
  }

  try {
    loadingStates.value.beneficiaries = true;
    errorStates.value.beneficiaries = null;

    const response = await axios.get(route("cdr.beneficiaries"), {
      params: {
        payrollID: payrollId,
        page,
        per_page: beneficiariesPagination.value.per_page,
      },
    });

    const { data, ...pagination } = response.data;
    beneficiaries.value = data;
    beneficiariesPagination.value = pagination;
    retryAttempts.value.beneficiaries = 0;
  } catch (error) {
    console.error("Beneficiary fetch error:", error);
    errorStates.value.beneficiaries =
      error.response?.data?.message || "Failed to load beneficiaries";

    // Implement retry logic
    if (!isRetry && retryAttempts.value.beneficiaries < MAX_RETRY_ATTEMPTS) {
      retryAttempts.value.beneficiaries++;
      await new Promise((resolve) =>
        setTimeout(resolve, 1000 * retryAttempts.value.beneficiaries)
      );
      return fetchBeneficiaries(payrollId, page, true);
    }
  } finally {
    loadingStates.value.beneficiaries = false;
  }
};

//fetch the dropdown options
const fetchDropdownOptions = async () => {
  try {
    const response = await axios.get(route("cdr.getOptions"));
    dropdownOptions.value = response.data;
  } catch (error) {
    console.error("Error fetching dropdown options:", error);
    alert.value = {
      show: true,
      message: "Failed to load dropdown options. Please refresh the page.",
      type: "error",
    };
  }
};

// Methods for modal
const openAddNewModal = () => {
  isModalOpen.value = true;
  selectedCategory.value = "";
  // Reset all form fields
  Object.keys(formData.value).forEach((key) => {
    formData.value[key] = "";
  });
};

const closeModal = () => {
  isModalOpen.value = false;
};

const saveNewItem = async () => {
  if (!selectedCategory.value) {
    alert("Please select a category");
    return;
  }

  // Validate required fields based on category
  if (!validateFields()) {
    alert("Please fill in all required fields");
    return;
  }

  try {
    const response = await axios.post(route("cdr.addOption"), {
      category: selectedCategory.value,
      data: formData.value,
    });

    // Update the corresponding dropdown options
    await fetchDropdownOptions();
    closeModal();

    // Show success message
    alert("Item added successfully");
  } catch (error) {
    console.error("Error saving new item:", error);
    alert(error.response?.data?.message || "Failed to save new item. Please try again.");
  }
};

//Client Side Validation
const validateFields = () => {
  switch (selectedCategory.value) {
    case "entityNameFundCluster":
      return formData.value.entityName && formData.value.fundCluster;
    case "officerDesignationStation":
      return (
        formData.value.accountableOfficer &&
        formData.value.officialDesignation &&
        formData.value.station
      );
    case "referenceNumber":
      return formData.value.dvPNumber && formData.value.checkNo;
    case "uacsCode":
      return formData.value.uacsObjectCode;
    case "natureOfPayment":
      return formData.value.natureOfPayment;
    default:
      return false;
  }
};

// Computed property to check if form is valid
const isFormValid = computed(() => {
  return (
    selectedCdr.value &&
    selectedFilters.value.entityNameFundCluster &&
    selectedFilters.value.officerDesignationStation &&
    selectedFilters.value.referenceNumber &&
    selectedFilters.value.uacsCode &&
    selectedFilters.value.natureOfPayment
  );
});

// Enhanced insertData with optimistic updates
const insertData = async () => {
  if (!isFormValid.value) return;

  const updateId = Date.now();
  try {
    loadingStates.value.insert = true;
    errorStates.value.insert = null;

    // Store optimistic update
    pendingUpdates.value.set(updateId, {
      ...selectedFilters.value,
      status: "pending",
    });

    const response = await axios.put(route("cdr.update", selectedCdr.value.cdrID), {
      entityID: selectedFilters.value.entityNameFundCluster,
      designationID: selectedFilters.value.officerDesignationStation,
      dvPNumber: selectedFilters.value.referenceNumber,
      uacsObjectCode: selectedFilters.value.uacsCode,
      nOPId: selectedFilters.value.natureOfPayment,
    });

    // Update successful
    pendingUpdates.value.set(updateId, {
      ...pendingUpdates.value.get(updateId),
      status: "success",
    });

    alert.value = {
      show: true,
      message: "Data inserted successfully!",
      type: "success",
    };

    selectedCdr.value = null;
    resetFilters();
  } catch (error) {
    console.error("Error inserting data:", error);

    // Mark optimistic update as failed
    pendingUpdates.value.set(updateId, {
      ...pendingUpdates.value.get(updateId),
      status: "failed",
    });

    errorStates.value.insert =
      error.response?.data?.message || "Failed to insert data. Please try again.";
  } finally {
    loadingStates.value.insert = false;
    // Remove optimistic update after some time
    setTimeout(() => {
      pendingUpdates.value.delete(updateId);
    }, 5000);
  }
};

// Field touched handler
const handleFieldTouched = (field) => {
  touchedFields.value.add(field);
  const errors = validateField(field, selectedFilters.value[field]);
  validationErrors.value = { ...validationErrors.value, ...errors };
};

// Reset filters
const resetFilters = () => {
  selectedFilters.value = {
    entityNameFundCluster: "",
    officerDesignationStation: "",
    referenceNumber: "",
    uacsCode: "",
    natureOfPayment: "",
  };
};

const debouncedSearch = debounce(() => {
  form.search = search.value;
  performSearch();
}, 300);

const sort = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  } else {
    sortField.value = field;
    sortDirection.value = "asc";
  }

  form.sort_field = sortField.value;
  form.sort_direction = sortDirection.value;
  performSearch();
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const retryFetchBeneficiaries = () => {
  if (selectedCdr.value?.payrollID) {
    fetchBeneficiaries(
      selectedCdr.value.payrollID,
      beneficiariesPagination.value.current_page
    );
  }
};

const viewBeneficiaries = (cdr) => {
  selectedCdr.value = cdr;
  if (!cdr.payrollID) {
    beneficiaryError.value = "No payroll ID associated with this CDR";
    return;
  }
  fetchBeneficiaries(cdr.payrollID);
};

const goBackToCdrList = () => {
  selectedCdr.value = null;
  beneficiaries.value = [];
  beneficiaryError.value = "";
  beneficiariesPagination.value = {
    current_page: 1,
    last_page: 1,
    from: 0,
    to: 0,
    total: 0,
    per_page: 12,
  };
};

const changeBeneficiariesPage = (page) => {
  if (page >= 1 && page <= beneficiariesPagination.value.last_page) {
    fetchBeneficiaries(selectedCdr.value.payrollID, page);
  }
};

// Lifecycle hook
onMounted(() => {
  search.value = form.search;
  setupAxiosInterceptors();

  // Load saved filters from localStorage
  const savedFilters = localStorage.getItem("cdrFilters");
  if (savedFilters) {
    selectedFilters.value = JSON.parse(savedFilters);
  }

  // Fetch initial dropdown data
  fetchDropdownOptions();
});

// Watch for filter changes
watch(
  selectedFilters,
  (newFilters) => {
    // Validate touched fields
    touchedFields.value.forEach((field) => {
      const errors = validateField(field, newFilters[field]);
      validationErrors.value = { ...validationErrors.value, ...errors };
    });

    localStorage.setItem("cdrFilters", JSON.stringify(newFilters));
  },
  { deep: true }
);

// Watch for CDR selection
watch(selectedCdr, (newCDR) => {
  if (!newCDR) {
    resetFilters();
  }
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

table {
  font-family: "Inter", sans-serif;
}

.bg-red-50 {
  background-color: rgba(254, 242, 242, 0.6);
}

/* Add hover effect for CDR table rows */
tbody tr:hover {
  background-color: rgba(243, 244, 246, 0.5);
}
</style>
