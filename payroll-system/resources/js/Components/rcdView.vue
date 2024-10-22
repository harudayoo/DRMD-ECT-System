<template>
    <div class="relative">
        <!-- Search Bar -->
        <div class="flex justify-end">
            <div v-if="!selectedRCD" class="w-[32%] mb-6">
                <label for="search" class="sr-only">Search RCDs</label>
                <div class="relative">
                    <input
                        id="search"
                        v-model="search"
                        @input="debouncedSearch"
                        type="text"
                        placeholder="Search by RCD Name or RCD Number"
                        class="bg-white text-gray-700 rounded-full pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full h-9 transition-all duration-300 ease-in-out"
                        aria-label="Search RCDs"
                    />
                    <div
                        class="absolute left-3 top-1/2 transform -translate-y-1/2"
                    >
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
            <span
                class="absolute top-0 bottom-0 right-0 px-4 py-3"
                @click="dismissError"
            >
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

        <!-- RCD Table -->
        <div
            v-if="!isLoading && !selectedRCD"
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
                        v-for="rcd in rcds.data"
                        :key="rcd.rcdID"
                        class="hover:bg-gray-100 cursor-pointer"
                        @click="selectRCD(rcd)"
                    >
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ rcd.rcdID }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ rcd.rcdName }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatDate(rcd.created_at) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Beneficiaries View (updated with client-side pagination and export button) -->
        <div
            v-if="selectedRCD"
            class="bg-white shadow mt-4 overflow-hidden sm:rounded-lg"
        >
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Beneficiaries for RCD: {{ selectedRCD.rcdName }}
                </h3>
                <div>
                    <button
                        @click="exportReport"
                        class="mr-2 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        Export Report
                    </button>
                    <button
                        @click="deselectRCD"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Back to RCD List
                    </button>
                </div>
            </div>

            <div v-if="selectedRCD">
                <DropdownWithAdd
                    label="DV/Payroll Number"
                    :items="dvPayrollItems"
                    v-model="selectedDvPayroll"
                    @add="openModal('dvPayroll')"
                    @change="handleDvPayrollChange"
                    :loading="isLoading"
                />
                <DropdownWithAdd
                    label="ORS/BURS Number"
                    :items="orsBursItems"
                    v-model="selectedOrsBurs"
                    @add="openModal('orsBurs')"
                    @change="handleOrsBursChange"
                    :loading="isLoading"
                />
                <DropdownWithAdd
                    label="Responsibility Center Code"
                    :items="respCodeItems"
                    v-model="selectedRespCode"
                    @add="openModal('respCode')"
                    @change="handleRespCodeChange"
                    :loading="isLoading"
                />
                <DropdownWithAdd
                    label="UACS Object Code"
                    :items="uacsCodeItems"
                    v-model="selectedUacsCode"
                    @add="openModal('uacsCode')"
                    @change="handleUacsCodeChange"
                    :loading="isLoading"
                />
                <DropdownWithAdd
                    label="Nature of Payment"
                    :items="paymentNatureItems"
                    v-model="selectedPaymentNature"
                    @add="openModal('paymentNature')"
                    @change="handlePaymentNatureChange"
                    :loading="isLoading"
                />

                <AddItemModal
                    v-if="showModal"
                    :modalType="modalType"
                    @close="closeModal"
                    @add="addItem"
                />
            </div>

            <div class="border-t border-gray-200">
                <div
                    v-if="isLoadingBeneficiaries"
                    class="flex justify-center items-center py-12"
                >
                    <div
                        class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"
                    ></div>
                </div>
                <table
                    v-else-if="paginatedBeneficiaries.length > 0"
                    class="min-w-full divide-y divide-gray-200"
                >
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
                            :key="beneficiary.id"
                        >
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ beneficiary.beneficiaryNumber }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ beneficiary.lastName }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ beneficiary.firstName }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ beneficiary.middleName }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ beneficiary.extensionName }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ beneficiary.amount }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm"
                                :class="getStatusClass(beneficiary.status)"
                            >
                                {{ getStatusText(beneficiary.status) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else-if="error" class="text-center py-8">
                    <p class="text-red-500 text-lg">{{ error }}</p>
                </div>
                <div v-else class="text-center py-8">
                    <p class="text-gray-500 text-lg">
                        No beneficiaries found for this RCD.
                    </p>
                </div>
            </div>

            <!-- Beneficiaries Pagination -->
            <div
                v-if="totalPages > 1"
                class="mt-6 flex justify-between items-center px-4 py-3 border-t border-gray-200 sm:px-6"
            >
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ paginationFrom }}</span>
                        to
                        <span class="font-medium">{{ paginationTo }}</span>
                        of
                        <span class="font-medium">{{
                            beneficiaries.length
                        }}</span>
                        results
                    </p>
                </div>
                <div>
                    <nav
                        class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                        aria-label="Pagination"
                    >
                        <button
                            v-for="page in paginationRange"
                            :key="page"
                            @click="currentPage = page"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                            :class="{
                                'z-10 bg-indigo-50 border-indigo-500 text-indigo-600':
                                    page === currentPage,
                                'bg-gray-100 text-gray-500 cursor-not-allowed':
                                    page === '...',
                            }"
                        >
                            {{ page }}
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Loading Indicator for RCDs -->
        <div
            v-if="isLoading && !selectedRCD"
            class="flex justify-center items-center py-12"
        >
            <div
                class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"
            ></div>
        </div>

        <!-- No Results Message -->
        <div
            v-if="!isLoading && !selectedRCD && rcds.data.length === 0"
            class="text-center py-8"
        >
            <p class="text-gray-500 text-lg">No RCDs found.</p>
        </div>

        <div
            v-if="apiError"
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
            role="alert"
        >
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline"> {{ apiError }}</span>
            <span
                class="absolute top-0 bottom-0 right-0 px-4 py-3"
                @click="apiError = null"
            >
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

        <!-- Pagination -->
        <div
            v-if="rcds.links.length > 3"
            class="mt-6 flex justify-between items-center"
        >
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ rcds.from }}</span>
                    to
                    <span class="font-medium">{{ rcds.to }}</span>
                    of
                    <span class="font-medium">{{ rcds.total }}</span>
                    results
                </p>
            </div>
            <div>
                <nav
                    class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                    aria-label="Pagination"
                >
                    <button
                        v-for="link in filteredLinks"
                        :key="link.label"
                        :disabled="!link.url"
                        @click="changePage(link.url)"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
                        :class="{
                            'z-10 bg-indigo-50 border-indigo-500 text-indigo-600':
                                link.active,
                            'bg-gray-100 text-gray-500 cursor-not-allowed':
                                !link.url,
                        }"
                        v-html="link.label"
                    ></button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { debounce } from "lodash-es";
import DropdownWithAdd from "./DropdownWithAdd.vue";
import AddItemModal from "./AddItemModal.vue";
import axios from "axios";

const props = defineProps({
    rcds: {
        type: Object,
        required: true,
    },
});

const search = ref("");
const isLoading = ref(false);
const error = ref(null);
const selectedRCD = ref(null);
const beneficiaries = ref([]);
const isLoadingBeneficiaries = ref(false);
const currentPage = ref(1);
const itemsPerPage = 6;
const showModal = ref(false);
const modalType = ref("");

const dvPayrollItems = ref([]);
const orsBursItems = ref([]);
const respCodeItems = ref([]);
const uacsCodeItems = ref([]);
const paymentNatureItems = ref([]);

const selectedDvPayroll = ref(null);
const selectedOrsBurs = ref(null);
const selectedRespCode = ref(null);
const selectedUacsCode = ref(null);
const selectedPaymentNature = ref(null);

const headers = ["RCD ID", "RCD Name", "Date Created"];
const beneficiaryHeaders = [
    "Beneficiary Number",
    "Last Name",
    "First Name",
    "Middle Name",
    "Extension Name",
    "Amount",
    "Status",
];

const apiError = ref(null);

const form = useForm({
    search: "",
});

const setError = (message) => {
    error.value = message;
    apiError.value = message;
};

const debouncedSearch = debounce(() => {
    performSearch();
}, 300);

const performSearch = () => {
    isLoading.value = true;
    error.value = null;
    form.get(route("rcd.index"), {
        preserveState: true,
        preserveScroll: true,
        only: ["rcds"],
        onSuccess: () => {
            isLoading.value = false;
        },
        onError: (errors) => {
            isLoading.value = false;
            error.value =
                "An error occurred while searching. Please try again.";
        },
    });
};

const changePage = (url) => {
    isLoading.value = true;
    error.value = null;
    form.get(url, {
        preserveState: true,
        preserveScroll: true,
        only: ["rcds"],
        onSuccess: () => {
            isLoading.value = false;
        },
        onError: (errors) => {
            isLoading.value = false;
            setError(
                "An error occurred while changing pages. Please try again."
            );
        },
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-PH", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const filteredLinks = computed(() => {
    return props.rcds.links.filter((link) => link.url !== null);
});

const dismissError = () => {
    error.value = null;
};

const selectRCD = (rcd) => {
    selectedRCD.value = rcd;
    selectedDvPayroll.value = rcd.dvNumber;
    selectedOrsBurs.value = rcd.orsNumber;
    selectedRespCode.value = rcd.responCode;
    selectedUacsCode.value = rcd.uacsCode;
    selectedPaymentNature.value = rcd.paymentNature;
    fetchBeneficiaries(rcd.payrollID);
};

const deselectRCD = () => {
    selectedRCD.value = null;
    beneficiaries.value = [];
    currentPage.value = 1;
};

const totalPages = computed(() =>
    Math.ceil(beneficiaries.value.length / itemsPerPage)
);

const paginatedBeneficiaries = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return beneficiaries.value.slice(start, end);
});

const paginationFrom = computed(
    () => (currentPage.value - 1) * itemsPerPage + 1
);
const paginationTo = computed(() =>
    Math.min(currentPage.value * itemsPerPage, beneficiaries.value.length)
);

const paginationRange = computed(() => {
    const range = [];
    for (let i = 1; i <= totalPages.value; i++) {
        if (
            i === 1 ||
            i === totalPages.value ||
            (i >= currentPage.value - 1 && i <= currentPage.value + 1)
        ) {
            range.push(i);
        } else if (i === currentPage.value - 2 || i === currentPage.value + 2) {
            range.push("...");
        }
    }
    return range;
});

const fetchBeneficiaries = async (payrollID) => {
    isLoadingBeneficiaries.value = true;
    error.value = null;

    try {
        const response = await fetch(
            `/rcd/beneficiaries?payrollID=${payrollID}`
        );
        if (!response.ok) {
            throw new Error("Failed to fetch beneficiaries");
        }
        const data = await response.json();
        beneficiaries.value = data;
        currentPage.value = 1; // Reset to first page when new data is fetched
    } catch (err) {
        error.value =
            "An error occurred while fetching beneficiaries. Please try again.";
        console.error(err);
    } finally {
        isLoadingBeneficiaries.value = false;
    }
};

const statusMap = {
    1: "Claimed",
    2: "Unclaimed",
    3: "Disqualified",
    4: "Double Entry",
};

const getStatusText = (statusCode) => {
    return statusMap[statusCode] || `Unknown (${statusCode})`;
};

const getStatusClass = (statusCode) => {
    switch (statusCode) {
        case 1:
            return "text-green-600";
        case 2:
            return "text-yellow-600";
        case 3:
            return "text-red-600";
        case 4:
            return "text-purple-600";
        default:
            return "text-gray-600";
    }
};

// for dropdown menu
const openModal = (type) => {
    modalType.value = type;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const fetchItems = async (endpoint, itemsRef) => {
    try {
        const response = await axios.get(`/api/${endpoint}`);
        if (endpoint === "paymentNature") {
            // Map to use nOPId as the value
            itemsRef.value = response.data.map((item) => ({
                ...item,
                id: item.nOPId,
                text: item.natureOfPayment,
            }));
        } else if (endpoint === "respCode") {
            // Map to use responsibilityCode as the value
            itemsRef.value = response.data.map((item) => ({
                ...item,
                id: item.responsibilityCode,
                text: item.responsibilityCode,
            }));
        } else {
            itemsRef.value = response.data;
        }
    } catch (error) {
        console.error(`Error fetching ${endpoint}:`, error);
        setError(`Failed to load ${endpoint} data. Please try again later.`);
    }
};

const addItem = async (type, value) => {
    try {
        const response = await axios.post(`/api/${type}`, { [type]: value });
        if (response.data) {
            // Update the corresponding items array
            switch (type) {
                case "dvPayroll":
                    dvPayrollItems.value.push(response.data);
                    selectedDvPayroll.value = response.data.id;
                    break;
                case "orsBurs":
                    orsBursItems.value.push(response.data);
                    selectedOrsBurs.value = response.data.id;
                    break;
                case "respCode":
                    respCodeItems.value.push(response.data);
                    selectedRespCode.value = response.data.id;
                    break;
                case "uacsCode":
                    uacsCodeItems.value.push(response.data);
                    selectedUacsCode.value = response.data.id;
                    break;
                case "paymentNature":
                    paymentNatureItems.value.push(response.data);
                    selectedPaymentNature.value = response.data.id;
                    break;
            }
        }
        closeModal();
    } catch (error) {
        console.error("Error adding item:", error);
        setError(`Failed to add ${type}. Please try again.`);
    }
};

const updateRCD = async (field, value) => {
    if (!selectedRCD.value) return;

    try {
        isLoading.value = true;

        // Process the value
        const actualValue =
            value?.id || value?.value || value?.target?.value || value;

        if (actualValue === undefined || actualValue === null) {
            throw new Error(`Invalid value for ${field}`);
        }

        const response = await axios.patch(
            `/api/rcd/${selectedRCD.value.rcdID}`,
            { [field]: actualValue },
            {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            }
        );

        if (response.data.success) {
            Object.assign(selectedRCD.value, response.data.data);
            updateSelectedValue(field, response.data.data[field]);
            showNotification(
                "Success",
                `Successfully updated ${field}`,
                "success"
            );
        } else {
            throw new Error(response.data.message || "Update failed");
        }
    } catch (error) {
        console.error(`Error updating RCD ${field}:`, error);
        handleUpdateError(error, field);
    } finally {
        isLoading.value = false;
    }
};

const handleUpdateError = (error, field) => {
    console.error(`Error updating RCD ${field}:`, error);

    let errorMessage = "An unexpected error occurred";

    if (error.response?.data?.errors) {
        errorMessage = Object.values(error.response.data.errors)
            .flat()
            .join(", ");
    } else if (error.response?.data?.message) {
        errorMessage = error.response.data.message;
    } else if (error.message) {
        errorMessage = error.message;
    }

    showNotification(
        "Error",
        `Failed to update ${field}: ${errorMessage}`,
        "error"
    );
    resetSelectedValue(field);
};

const updateSelectedValue = (field, value) => {
    console.log(`Updating ${field} with value:`, value);
    switch (field) {
        case "dvNumber":
            selectedDvPayroll.value = value;
            break;
        case "orsNumber":
            selectedOrsBurs.value = value;
            break;
        case "responCode":
            selectedRespCode.value = value;
            break;
        case "uacsCode":
            selectedUacsCode.value = value;
            break;
        case "paymentNature":
            selectedPaymentNature.value = value;
            break;
    }
};

const resetSelectedValue = (field) => {
    switch (field) {
        case "dvNumber":
            selectedDvPayroll.value = selectedRCD.value.dvNumber;
            break;
        case "orsNumber":
            selectedOrsBurs.value = selectedRCD.value.orsNumber;
            break;
        case "responCode":
            selectedRespCode.value = selectedRCD.value.responCode;
            break;
        case "uacsCode":
            selectedUacsCode.value = selectedRCD.value.uacsCode;
            break;
        case "paymentNature":
            selectedPaymentNature.value = selectedRCD.value.paymentNature;
            break;
    }
};

// Add a notification system
const notifications = ref([]);
const showNotification = (title, message, type = "info") => {
    const id = Date.now();
    notifications.value.push({ id, title, message, type });
    setTimeout(() => {
        notifications.value = notifications.value.filter((n) => n.id !== id);
    }, 5000);
};

//Computed properties
const selectedValues = computed(() => ({
    dvNumber: selectedRCD.value?.dvNumber,
    orsNumber: selectedRCD.value?.orsNumber,
    responCode: selectedRCD.value?.responCode,
    uacsCode: selectedRCD.value?.uacsCode,
    paymentNature: selectedRCD.value?.paymentNature,
}));

//Watchers

watch(search, (value) => {
    form.search = value;
    debouncedSearch();
});

watch(currentPage, () => {});

// Dropdown watchers
watch(selectedDvPayroll, (newValue) => {
    if (selectedRCD.value && newValue !== selectedValues.value.dvNumber) {
        updateRCD("dvNumber", newValue);
    }
});

watch(selectedOrsBurs, (newValue) => {
    if (selectedRCD.value && newValue !== selectedValues.value.orsNumber) {
        updateRCD("orsNumber", newValue);
    }
});

watch(selectedRespCode, (newValue) => {
    if (selectedRCD.value && newValue !== selectedValues.value.responCode) {
        // Extract the actual value if it's an event object
        const actualValue = newValue?.target?.value ?? newValue?.id ?? newValue;
        if (actualValue !== selectedValues.value.responCode) {
            updateRCD("responCode", actualValue);
        }
    }
});

watch(selectedUacsCode, (newValue) => {
    if (selectedRCD.value && newValue !== selectedValues.value.uacsCode) {
        updateRCD("uacsCode", newValue);
    }
});

watch(selectedPaymentNature, (newValue) => {
    if (selectedRCD.value && newValue !== selectedValues.value.paymentNature) {
        updateRCD("paymentNature", newValue);
    }
});

onMounted(async () => {
    search.value = form.search;

    fetchItems("dvPayroll", dvPayrollItems);
    fetchItems("orsBurs", orsBursItems);
    fetchItems("respCode", respCodeItems);
    fetchItems("uacsCode", uacsCodeItems);
    fetchItems("paymentNature", paymentNatureItems);
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

table {
    font-family: "Inter", sans-serif;
}
</style>
