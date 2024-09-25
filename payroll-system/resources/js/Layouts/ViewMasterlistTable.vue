<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Search Bar -->
    <div v-if="!showBeneficiaries" class="mb-6">
      <input
        v-model="searchQuery"
        @input="searchMasterlists"
        type="text"
        placeholder="Search by ID, Municipality, or Name"
        class="w-full md:w-1/2 lg:w-1/3 px-4 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
      />
    </div>

    <!-- Back Button (visible when showing beneficiaries) -->
    <div v-if="showBeneficiaries" class="mb-6">
      <!-- Search Bar -->
      <div class="mb-6">
        <input
          v-model="searchQuery"
          @input="showBeneficiaries ? searchBeneficiaries() : searchMasterlists()"
          type="text"
          :placeholder="
            showBeneficiaries
              ? 'Search by Beneficiary Number, Name, or Status'
              : 'Search by ID, Municipality, or Name'
          "
          class="w-full md:w-1/2 lg:w-1/3 px-4 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        />
      </div>

      <button
        @click="goBackToMasterlists"
        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
      >
        ← Back to Masterlists
      </button>
      <button
        @click="exportMasterlist"
        :disabled="isLoading"
        class="flex-auto px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
      >
        Export Masterlist
      </button>
    </div>

    <!-- Error Message -->
    <div
      v-else-if="error"
      class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
      role="alert"
    >
      <strong class="font-bold">Error!</strong>
      <span class="block sm:inline"> {{ error }}</span>
    </div>

    <!-- Error Notification -->
    <div
      v-if="error"
      class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50"
      role="alert"
    >
      <strong class="font-bold">Error!</strong>
      <span class="block sm:inline"> {{ error }}</span>
    </div>

    <!-- Export Modal -->
    <div
      v-if="showExportModal"
      class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50"
    >
      <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="flex justify-center items-center py-12">
          <div
            class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"
          ></div>
        </div>
        <p class="text-center text-gray-700">Exporting Masterlist...</p>
      </div>
    </div>

    <!-- Masterlist Table -->
    <div
      v-else-if="!showBeneficiaries"
      class="bg-white shadow overflow-hidden sm:rounded-lg"
    >
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              v-for="header in headers"
              :key="header"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              {{ header }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="masterlist in paginatedMasterlists"
            :key="masterlist.masterlistID"
            @click="fetchBeneficiaries(masterlist.masterlistID)"
            class="hover:bg-gray-100 cursor-pointer"
          >
            <td
              v-for="key in displayFields"
              :key="key"
              class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
            >
              <template v-if="key === 'municipality.municipalityName'">
                {{
                  masterlist.municipality
                    ? masterlist.municipality.municipalityName
                    : "N/A"
                }}
              </template>
              <template v-else-if="key === 'totalAmountReleased'">
                {{ formatCurrency(masterlist[key]) }}
              </template>
              <template v-else>
                {{ masterlist[key] }}
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Beneficiaries Table -->
    <div
      v-else-if="showBeneficiaries"
      class="bg-white shadow overflow-hidden sm:rounded-lg"
    >
      <h2 class="text-xl font-semibold mb-4 px-6 py-4 bg-gray-50">
        Beneficiaries for Masterlist {{ selectedMasterlistID }}
      </h2>
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              v-for="header in beneficiaryHeaders"
              :key="header"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              {{ header }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="beneficiary in paginatedBeneficiaries"
            :key="beneficiary.beneficiaryNumber"
            class="hover:bg-gray-100"
          >
            <td
              v-for="key in beneficiaryDisplayFields"
              :key="key"
              class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
            >
              {{ key === "status" ? getStatusText(beneficiary[key]) : beneficiary[key] }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Loading Indicator -->
    <div v-if="isLoading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>

    <!-- Pagination -->
    <div v-if="!isLoading && !error" class="mt-6 flex justify-between items-center">
      <div>
        <p class="text-sm text-gray-700">
          Showing
          <span class="font-medium">{{ currentStartIndex }}</span>
          to
          <span class="font-medium">{{ currentEndIndex }}</span>
          of
          <span class="font-medium">{{ totalItems }}</span>
          results
        </p>
      </div>
      <div>
        <nav
          class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <button
            @click="previousPage"
            :disabled="currentPage === 1"
            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            :class="{
              'opacity-50 cursor-not-allowed': currentPage === 1,
            }"
          >
            Previous
          </button>
          <button
            v-for="page in displayedPageNumbers"
            :key="page"
            @click="goToPage(page)"
            :class="[
              currentPage === page
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
            ]"
          >
            {{ page }}
          </button>
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            :class="{
              'opacity-50 cursor-not-allowed': currentPage === totalPages,
            }"
          >
            Next
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import axios from "axios";
import { format } from "date-fns";

const masterlists = ref([]);
const beneficiaries = ref([]);
const searchQuery = ref("");
const showBeneficiaries = ref(false);
const selectedMasterlistID = ref(null);
const isLoading = ref(false);
const error = ref(null);
const showExportModal = ref(false);

const headers = ["Masterlist ID", "Municipality", "List Name", "Number of Beneficiaries"];

const displayFields = [
  "masterlistID",
  "municipality.municipalityName",
  "masterlistName",
  "totalBeneficiaries",
];

const beneficiaryHeaders = [
  "Beneficiary Number",
  "Last Name",
  "First Name",
  "Middle Name",
  "Extension Name",
  "Date of Birth",
  "Sex",
  "Status",
];

const beneficiaryDisplayFields = [
  "beneficiaryNumber",
  "lastName",
  "firstName",
  "middleName",
  "extensionName",
  "dateOfBirth",
  "sex",
  "status",
];

// Fetch masterlists data
const fetchMasterlists = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await axios.get("/api/masterlists");
    masterlists.value = response.data.masterlists;
  } catch (err) {
    error.value = "Failed to fetch masterlists. Please try again.";
    console.error("Error fetching masterlists:", err);
  } finally {
    isLoading.value = false;
  }
};

onMounted(fetchMasterlists);

// Format currency
const formatCurrency = (value) => {
  return new Intl.NumberFormat("en-PH", {
    style: "currency",
    currency: "PHP",
  }).format(value);
};

// Search functionality
const filteredMasterlists = computed(() => {
  if (!searchQuery.value) return masterlists.value;

  const lowercaseQuery = searchQuery.value.toLowerCase();
  return masterlists.value.filter((masterlist) => {
    return (
      masterlist.masterlistID.toLowerCase().includes(lowercaseQuery) ||
      (masterlist.municipality &&
        masterlist.municipality.municipalityName
          .toLowerCase()
          .includes(lowercaseQuery)) ||
      masterlist.masterlistName.toLowerCase().includes(lowercaseQuery)
    );
  });
});

const searchMasterlists = () => {
  currentPage.value = 1;
};

// Pagination logic
const itemsPerPage = 10;
const currentPage = ref(1);

const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage));

const totalItems = computed(() =>
  showBeneficiaries.value ? beneficiaries.value.length : filteredMasterlists.value.length
);

const currentStartIndex = computed(() => (currentPage.value - 1) * itemsPerPage + 1);
const currentEndIndex = computed(() =>
  Math.min(currentStartIndex.value + itemsPerPage - 1, totalItems.value)
);

const paginatedMasterlists = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredMasterlists.value.slice(start, end);
});

const paginatedBeneficiaries = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return beneficiaries.value.slice(start, end);
});

const displayedPageNumbers = computed(() => {
  const range = 2;
  const pages = [];
  for (
    let i = Math.max(1, currentPage.value - range);
    i <= Math.min(totalPages.value, currentPage.value + range);
    i++
  ) {
    pages.push(i);
  }
  return pages;
});

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const goToPage = (page: number) => {
  currentPage.value = page;
};

// Updated the fetchBeneficiaries function to include search functionality
const fetchBeneficiaries = async (masterlistID, search = "") => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await axios.get(`/api/masterlists/${masterlistID}/beneficiaries`, {
      params: { search },
    });
    beneficiaries.value = response.data.beneficiaries;
    selectedMasterlistID.value = masterlistID;
    currentPage.value = 1;
    showBeneficiaries.value = true;
  } catch (err) {
    error.value = "Failed to fetch beneficiaries. Please try again.";
    console.error("Error fetching beneficiaries:", err);
  } finally {
    isLoading.value = false;
  }
};

//function to handle beneficiary search
const searchBeneficiaries = () => {
  if (selectedMasterlistID.value) {
    fetchBeneficiaries(selectedMasterlistID.value, searchQuery.value);
  }
};

const goBackToMasterlists = () => {
  showBeneficiaries.value = false;
  selectedMasterlistID.value = null;
  currentPage.value = 1;
};

const getStatusText = (status: number) => {
  const statusMap = {
    1: "Claimed",
    2: "Unclaimed",
    3: "Disqualified",
    4: "Duplicate",
  };
  return statusMap[status as keyof typeof statusMap] || "Unknown";
};

//function to export the masterlist
const exportMasterlist = async () => {
  showExportModal.value = true;
  isLoading.value = true;
  error.value = null;

  try {
    const response = await axios.get(
      `/api/masterlists/${selectedMasterlistID.value}/export`,
      {
        responseType: "blob",
      }
    );
    const blob = new Blob([response.data], {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute(
      "download",
      `${
        masterlists.value.find((m) => m.masterlistID === selectedMasterlistID.value)
          ?.masterlistName
      }.xlsx`
    );
    document.body.appendChild(link);
    link.click();
    link.remove();
    showExportModal.value = false;
  } catch (err) {
    handleExportError("Failed to export masterlist. Please try again.");
    console.error("Error exporting masterlist:", err);
  } finally {
    isLoading.value = false;
  }
};

const handleExportError = (errorMessage: string) => {
  error.value = errorMessage;
  setTimeout(() => {
    error.value = null;
  }, 5000);
};

//function to close the export modal when the user clicks outside of it
const closeExportModal = () => {
  showExportModal.value = false;
};

// Add a new event listener to close the export modal when the user clicks outside of it
onMounted(() => {
  document.addEventListener("click", closeExportModal);
});

// Updated the watch function to include beneficiary search
watch([showBeneficiaries, searchQuery], () => {
  currentPage.value = 1;
  if (showBeneficiaries.value) {
    searchBeneficiaries();
  } else {
    fetchMasterlists();
  }
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

table {
  font-family: "Inter", sans-serif;
}
</style>
