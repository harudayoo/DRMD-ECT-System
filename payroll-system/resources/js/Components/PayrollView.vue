<template>
  <div class="relative">
    <!-- Search Bar -->
    <div class="flex justify-end">
      <div v-if="!selectedPayroll" class="w-[47%] mb-5 mt-1">
        <label for="search" class="sr-only">Search Payrolls</label>
        <div class="relative">
          <input
            id="search"
            v-model="search"
            @input="debouncedSearch"
            type="text"
            placeholder="Search by Payroll Number, Name, Municipality, Barangay, or Date"
            class="bg-white text-gray-700 rounded-full pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full h-9 transition-all duration-300 ease-in-out"
            aria-label="Search Payrolls"
          />
          <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="3"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="lucide lucide-search text-gray-400"
            >
              <circle cx="11" cy="11" r="8" />
              <path d="m21 21-4.3-4.3" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Error Message -->
    <div
      v-if="error"
      class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
      role="alert"
    >
      <strong class="font-bold">Error!</strong>
      <span class="block sm:inline"> {{ error }}</span>
      <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="dismissError">
        <svg
          class="fill-current h-6 w-6 text-red-500"
          role="button"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
        >
          <title>Close</title>
          <path
            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"
          />
        </svg>
      </span>
    </div>

    <!-- Payroll Table -->
    <div
      v-if="!isLoading && !selectedPayroll"
      class="bg-white shadow overflow-hidden sm:rounded-lg"
    >
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-white">
          <tr>
            <th
              v-for="header in headers"
              :key="header"
              class="px-5 py-4 text-center text-sm font-medium text-black uppercase tracking-wider"
            >
              {{ header }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="payroll in payrolls.data"
            :key="payroll.payrollID"
            v-memo="[
              payroll.payrollID,
              payroll.payrollNumber,
              payroll.payrollName,
              payroll.municipalityName,
              payroll.barangayName,
              payroll.subTotal,
              payroll.created_at,
            ]"
            class="hover:bg-gray-50 cursor-pointer"
            @click="selectPayroll(payroll)"
          >
            <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-900">
              {{ payroll.payrollNumber }}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-900">
              {{ payroll.payrollName }}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-900">
              {{ payroll.municipalityName }}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-900">
              {{ payroll.barangayName }}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-900">
              {{ formatSubTotal(payroll.subTotal) }}
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-900">
              {{ formatDate(payroll.created_at) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Loading Indicator for Payrolls -->
    <div
      v-if="isLoading && !selectedPayroll"
      class="flex justify-center items-center py-12"
    >
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>

    <!-- No Results Message -->
    <div
      v-if="!isLoading && !selectedPayroll && payrolls.data.length === 0"
      class="text-center py-8"
    >
      <p class="text-gray-500 text-lg">No payrolls found.</p>
    </div>

    <!-- Beneficiaries Section -->
    <div v-if="selectedPayroll" class="-mt-1">
      <!-- Back Button -->
      <button
        @click="goBackToPayrolls"
        class="mb-2 px-4 py-1.5 bg-blue-900 text-white rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
      >
        &larr; Back to Payrolls
      </button>
      <h2 class="text-2xl font-medium mb-4">
        Beneficiaries for {{ selectedPayroll.payrollName }}
        <span
          v-if="selectedPayroll.exportNum > 0"
          class="text-sm font-normal text-gray-600 ml-2"
        >
          (exported {{ selectedPayroll.exportNum }} time{{
            selectedPayroll.exportNum > 1 ? "s" : ""
          }}
          with latest export at
          {{ formatDateTime(selectedPayroll.updated_at) }})
        </span>
      </h2>

      <!-- Search and Set Amount Section -->
      <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-6"
      >
        <div class="w-full sm:w-1/2">
          <input
            v-model="beneficiarySearch"
            @input="debouncedBeneficiarySearch"
            type="text"
            placeholder="Search Beneficiaries by Number or Name"
            class="w-full px-4 py-1.5 border rounded-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <div class="w-full sm:w-1/2 flex items-center">
          <h1 class="text-xl font-bold ml-3 mr-3">PHP:</h1>
          <input
            v-model="beneficiaryAmount"
            type="text"
            placeholder="Set Amount"
            class="flex-grow px-6 py-1.5 border rounded-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mr-2"
            @input="beneficiaryAmount = formatAmount($event.target.value)"
          />
          <button
            @click="setAmount"
            class="px-4 py-1.5 bg-blue-900 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-300 whitespace-nowrap"
            :disabled="amountError !== ''"
          >
            Set Amount
          </button>
        </div>
        <p v-if="amountError" class="text-red-500 mt-2">
          {{ amountError }}
        </p>
        <div>
          <button
            @click="exportPayroll"
            class="px-4 py-1.5 bg-red-700 text-white rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 whitespace-nowrap"
          >
            {{ isExporting ? "Exporting..." : "Export Payroll" }}
          </button>
        </div>
        <button
          @click="markAllClaimed"
          class="px-4 py-1.5 bg-green-700 text-white rounded-full hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 whitespace-nowrap ml-2"
        >
          Mark as All Claimed
        </button>
      </div>

      <!-- Loading Indicator for Beneficiaries -->
      <div v-if="isBeneficiariesLoading" class="flex justify-center items-center py-12">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"
        ></div>
      </div>

      <!-- Beneficiaries Table -->
      <div
        v-if="!isBeneficiariesLoading && beneficiaries.length > 0"
        class="bg-white shadow rounded-lg overflow-hidden"
      >
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
              <div class="max-h-[calc(100vh-300px)] overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-white sticky top-0">
                    <tr>
                      <th
                        v-for="header in beneficiaryHeaders"
                        :key="header"
                        scope="col"
                        class="px-5 py-4 text-center text-sm font-medium text-black uppercase tracking-wider"
                      >
                        {{ header }}
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                      v-for="beneficiary in beneficiaries"
                      :key="beneficiary.beneficiaryID"
                    >
                      <td
                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500"
                      >
                        {{ beneficiary.beneficiaryNumber }}
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900"
                      >
                        {{ beneficiary.lastName }}
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900"
                      >
                        {{ beneficiary.firstName }}
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900"
                      >
                        {{ beneficiary.middleName }}
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900"
                      >
                        {{ beneficiary.extensionName || "N/A" }}
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900"
                      >
                        {{ formatCurrency(beneficiary.amount) }}
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900"
                      >
                        <select
                          v-model="beneficiary.status"
                          @change="updateBeneficiaryStatus(beneficiary)"
                          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                          <option value="1">Claimed</option>
                          <option value="2">Unclaimed</option>
                          <option value="3">Disqualified</option>
                          <option value="4">Double Entry</option>
                        </select>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Beneficiaries Pagination -->
    <div v-if="beneficiariesPagination" class="mt-6 flex justify-between items-center">
      <div>
        <p class="text-sm text-gray-700">
          Showing
          <span class="font-medium">{{ beneficiariesPagination.from || 0 }}</span>
          to
          <span class="font-medium">{{ beneficiariesPagination.to || 0 }}</span>
          of
          <span class="font-medium">{{ beneficiariesPagination.total || 0 }}</span>
          results
        </p>
      </div>
      <div>
        <nav
          class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <button
            v-for="link in beneficiariesPagination.links"
            :key="link.label"
            :disabled="!link.url"
            @click="changeBeneficiariesPage(link.url)"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
            :class="{
              'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': link.active,
              'bg-gray-100 text-gray-500 cursor-not-allowed': !link.url,
            }"
            v-html="link.label"
          ></button>
        </nav>
      </div>
    </div>

    <!-- No Beneficiaries Message -->
    <div
      v-if="!isBeneficiariesLoading && selectedPayroll && beneficiaries.length === 0"
      class="text-center py-8"
    >
      <p class="text-gray-500 text-lg">No Beneficiary Found In This Payroll.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import { debounce } from "lodash-es";
import axios from "axios";
import moment from "moment";

const props = defineProps({
  payrolls: Object,
});

const search = ref("");
const isLoading = ref(false);
const isBeneficiariesLoading = ref(false);
const error = ref(null);
const selectedPayroll = ref(null);
const beneficiaries = ref([]);
const beneficiaryAmount = ref(null);
const amountError = ref("");

const beneficiarySearch = ref("");
const beneficiariesPerPage = 10;
const isExporting = ref(false);
const beneficiariesPagination = ref(null);

const headers = [
  "Payroll Number",
  "Payroll Name",
  "Municipality",
  "Barangay",
  "Total Amount",
  "Date Created",
];

const beneficiaryHeaders = [
  "Beneficiary Number",
  "Last Name",
  "First Name",
  "Middle Name",
  "Extension Name",
  "Amount",
  "Status",
];

const form = useForm({
  search: "",
});

const setError = (message) => {
  error.value = message;
};

const debouncedSearch = debounce(() => {
  performSearch();
}, 300);

const debouncedBeneficiarySearch = debounce(() => {
  fetchBeneficiaries(selectedPayroll.value.payrollID, 1, beneficiarySearch.value);
}, 300);

const performSearch = () => {
  isLoading.value = true;
  error.value = null;
  form.get(route("payroll.index"), {
    preserveState: true,
    preserveScroll: true,
    only: ["payrolls"],
    onSuccess: () => {
      isLoading.value = false;
    },
    onError: (errors) => {
      isLoading.value = false;
      error.value = "An error occurred while searching. Please try again.";
    },
  });
};

const changePage = (url) => {
  isLoading.value = true;
  error.value = null;
  form.get(url, {
    preserveState: true,
    preserveScroll: true,
    only: ["payrolls"],
    onSuccess: () => {
      isLoading.value = false;
    },
    onError: (errors) => {
      isLoading.value = false;
      setError("An error occurred while changing pages. Please try again.");
    },
  });
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat("en-PH", {
    style: "currency",
    currency: "PHP",
  }).format(value);
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString("en-PH", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const formatDateTime = (dateTime) => {
  return moment(dateTime).format("MMMM D, YYYY [at] h:mm A");
};

const filteredLinks = computed(() => {
  return props.payrolls.links.filter((link) => link.url !== null);
});

const dismissError = () => {
  error.value = null;
};

watch(search, (value) => {
  form.search = value;
  debouncedSearch();
});

onMounted(() => {
  search.value = form.search;
});

//Payroll Beneficiary List
const selectPayroll = async (payroll) => {
  selectedPayroll.value = payroll;
  beneficiarySearch.value = "";
  await fetchBeneficiaries(payroll.payrollID);
};

const fetchBeneficiaries = async (payrollId, page = 1, search = "") => {
  isBeneficiariesLoading.value = true;
  try {
    const response = await axios.get(`/payroll/${payrollId}/beneficiaries`, {
      params: { page, per_page: beneficiariesPerPage, search },
    });
    beneficiaries.value = response.data.beneficiaries.data;
    beneficiariesPagination.value = {
      current_page: response.data.beneficiaries.current_page,
      from: response.data.beneficiaries.from,
      to: response.data.beneficiaries.to,
      total: response.data.beneficiaries.total,
      last_page: response.data.beneficiaries.last_page,
      links: response.data.beneficiaries.links,
    };

    selectedPayroll.value = {
      ...response.data.payroll,
      exportNum: response.data.payroll.exportNum,
      updated_at: response.data.payroll.updated_at,
    };
  } catch (err) {
    setError("An error occurred while fetching beneficiaries. Please try again.");
  } finally {
    isBeneficiariesLoading.value = false;
  }
};

const changeBeneficiariesPage = async (url) => {
  if (!url) return;

  const urlParams = new URL(url).searchParams;
  const page = urlParams.get("page");
  await fetchBeneficiaries(
    selectedPayroll.value.payrollID,
    page,
    beneficiarySearch.value
  );
};

const formatAmount = (value) => {
  // Remove any non-digit characters except for the decimal point
  let formatted = value.replace(/[^\d.]/g, "");

  // Ensure only one decimal point
  const parts = formatted.split(".");
  if (parts.length > 2) {
    formatted = parts[0] + "." + parts.slice(1).join("");
  }

  // Limit to two decimal places
  if (parts.length > 1) {
    formatted = parts[0] + "." + parts[1].slice(0, 2);
  }

  // Add thousands separators
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

  return parts.join(".");
};

const parseAmount = (value) => {
  // Remove thousand separators and parse as float
  return parseFloat(value.replace(/,/g, ""));
};

const validateAmount = (value) => {
  const numValue = parseAmount(value);
  if (isNaN(numValue) || numValue < 0 || numValue > 999999.99) {
    amountError.value = "Amount must be between 0 and 999,999.99";
    return false;
  }
  amountError.value = "";
  return true;
};

const formatSubTotal = (value) => {
  // Parse the value to a float and limit it to 2 decimal places
  let formattedValue = parseFloat(value).toFixed(2);

  // Ensure the value doesn't exceed 99999.99
  formattedValue = Math.min(formattedValue, 999999.99);

  // Format the number with commas for thousands
  return new Intl.NumberFormat("en-US", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(formattedValue);
};

// Update the refreshPayrollData function
const refreshPayrollData = async () => {
  try {
    const response = await axios.get(`/payroll/${selectedPayroll.value.payrollID}`);
    selectedPayroll.value = {
      ...response.data.payroll,
      subTotal: formatSubTotal(response.data.payroll.subTotal),
    };
    await fetchBeneficiaries(selectedPayroll.value.payrollID);
  } catch (err) {
    setError("An error occurred while refreshing payroll data. Please try again.");
  }
};

// Update the setAmount function
const setAmount = async () => {
  if (!selectedPayroll.value || !beneficiaryAmount.value) return;

  if (!validateAmount(beneficiaryAmount.value)) {
    setError(amountError.value);
    return;
  }

  const parsedAmount = parseFloat(parseAmount(beneficiaryAmount.value)).toFixed(2);

  try {
    isBeneficiariesLoading.value = true;
    await axios.post(`/payroll/${selectedPayroll.value.payrollID}/beneficiaries`, {
      amount: parsedAmount,
      beneficiaries: beneficiaries.value.map((b) => ({
        beneficiaryID: b.beneficiaryID,
        status: b.status,
      })),
    });
    // Update local beneficiary amounts
    beneficiaries.value = beneficiaries.value.map((b) => ({
      ...b,
      amount: parsedAmount,
    }));
    await fetchBeneficiaries(selectedPayroll.value.payrollID);
    await refreshPayrollData(); // Refresh payroll data to get updated subtotal
  } catch (err) {
    setError(
      "An error occurred while updating beneficiary amounts: " +
        (err.response?.data?.error || err.message)
    );
    console.error(err);
  } finally {
    isBeneficiariesLoading.value = false;
  }
};

const updateBeneficiaryStatus = async (beneficiary) => {
  try {
    isBeneficiariesLoading.value = true;
    await axios.post(`/payroll/${selectedPayroll.value.payrollID}/beneficiaries`, {
      amount: beneficiary.amount,
      beneficiaries: [
        {
          beneficiaryID: beneficiary.beneficiaryID,
          status: beneficiary.status,
        },
      ],
    });
    await fetchBeneficiaries(selectedPayroll.value.payrollID);
  } catch (err) {
    setError("An error occurred while updating beneficiary status. Please try again.");
    // Revert the status change in the UI
    beneficiary.status = beneficiary.status === 1 ? 2 : 1;
  } finally {
    isBeneficiariesLoading.value = false;
  }
};

watch(beneficiarySearch, (value) => {
  debouncedBeneficiarySearch();
});

const goBackToPayrolls = () => {
  selectedPayroll.value = null;
  beneficiaries.value = [];
  beneficiariesPagination.value = null;
};

const markAllClaimed = async () => {
  if (!selectedPayroll.value) return;

  try {
    await axios.post(`/payroll/${selectedPayroll.value.payrollID}/mark-all-claimed`);
    await fetchBeneficiaries(selectedPayroll.value.payrollID);
    setError("All beneficiaries have been marked as claimed.");
  } catch (err) {
    setError(
      "An error occurred while marking beneficiaries as claimed. Please try again."
    );
  }
};

const exportPayroll = async () => {
  if (!selectedPayroll.value) return;

  isExporting.value = true;
  setError(null);

  try {
    const response = await axios.get(
      `/payroll/${selectedPayroll.value.payrollID}/export`,
      {
        responseType: "blob",
        validateStatus: function (status) {
          return status >= 200 && status < 500; // Resolve only if the status code is less than 500
        },
      }
    );

    const contentType = response.headers["content-type"];

    if (contentType === "application/pdf") {
      // Handle successful PDF download
      const blob = new Blob([response.data], { type: contentType });
      const link = document.createElement("a");
      link.href = window.URL.createObjectURL(blob);
      link.download = `payroll_${selectedPayroll.value.payrollNumber}.pdf`;
      link.click();
      window.URL.revokeObjectURL(link.href);
    } else if (contentType === "application/json") {
      // Handle JSON responses (for errors or messages)
      const reader = new FileReader();
      reader.onload = () => {
        const result = JSON.parse(reader.result);
        if (result.error) {
          setError(result.error);
        } else if (result.message) {
          setError(result.message);
        }
      };
      reader.readAsText(response.data);
    } else {
      // Handle unexpected content type
      setError("Unexpected response from server");
    }
  } catch (err) {
    console.error(err);
    setError("An error occurred while exporting the payroll. Please try again.");
  } finally {
    isExporting.value = false;
    await fetchBeneficiaries(selectedPayroll.value.payrollID);
  }
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

table {
  font-family: "Inter", sans-serif;
}
</style>
