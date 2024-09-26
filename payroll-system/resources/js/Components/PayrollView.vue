<template>
    <div class="container mx-auto px-2 py-1">
        <!-- Search Bar -->
        <div v-if="!selectedPayroll" class="mb-6">
            <label for="search" class="sr-only">Search Payrolls</label>
            <input
                id="search"
                v-model="search"
                @input="debouncedSearch"
                type="text"
                placeholder="Search by Payroll Number, Name, Municipality, Barangay, or Date"
                class="w-full md:w-1/2 lg:w-1/3 px-4 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                aria-label="Search Payrolls"
            />
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

        <!-- Payroll Table -->
        <div
            v-if="!isLoading && !selectedPayroll"
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
                        class="hover:bg-gray-100 cursor-pointer"
                        @click="selectPayroll(payroll)"
                    >
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ payroll.payrollNumber }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ payroll.payrollName }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ payroll.municipalityName }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ payroll.barangayName }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatCurrency(payroll.subTotal) }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
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
            <div
                class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"
            ></div>
        </div>

        <!-- No Results Message -->
        <div
            v-if="!isLoading && !selectedPayroll && payrolls.data.length === 0"
            class="text-center py-8"
        >
            <p class="text-gray-500 text-lg">No payrolls found.</p>
        </div>

        <!-- Beneficiaries Section -->
        <div v-if="selectedPayroll" class="mt-8">
            <!-- Back Button -->
            <button
                @click="goBackToPayrolls"
                class="mb-4 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
            >
                &larr; Back to Payrolls
            </button>
            <h2 class="text-2xl font-semibold mb-4">
                Beneficiaries for {{ selectedPayroll.payrollName }}
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
                        placeholder="Search beneficiaries by number, name"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>
                <div class="w-full sm:w-1/2 flex items-center">
                    <h1 class="text-3xl ml-6 mr-3">₱:</h1>
                    <input
                        v-model="beneficiaryAmount"
                        type="number"
                        placeholder="Set Amount for Beneficiaries"
                        class="flex-grow px-4 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mr-2"
                    />
                    <button
                        @click="setAmount"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 whitespace-nowrap"
                    >
                        Set Amount
                    </button>
                </div>
            </div>

            <!-- Loading Indicator for Beneficiaries -->
            <div
                v-if="isBeneficiariesLoading"
                class="flex justify-center items-center py-12"
            >
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
                        <div
                            class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg"
                        >
                            <div
                                class="max-h-[calc(100vh-300px)] overflow-y-auto"
                            >
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th
                                                v-for="header in beneficiaryHeaders"
                                                :key="header"
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                {{ header }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200"
                                    >
                                        <tr
                                            v-for="beneficiary in beneficiaries"
                                            :key="beneficiary.beneficiaryID"
                                        >
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                            >
                                                {{
                                                    beneficiary.beneficiaryNumber
                                                }}
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
                                                {{
                                                    formatCurrency(
                                                        beneficiary.amount
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                            >
                                                <select
                                                    v-model="beneficiary.status"
                                                    @change="
                                                        updateBeneficiaryStatus(
                                                            beneficiary
                                                        )
                                                    "
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                >
                                                    <option value="1">
                                                        Claimed
                                                    </option>
                                                    <option value="2">
                                                        Unclaimed
                                                    </option>
                                                    <option value="3">
                                                        Disqualified
                                                    </option>
                                                    <option value="4">
                                                        Double Entry
                                                    </option>
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
        <div
            v-if="beneficiariesPagination"
            class="mt-6 flex justify-between items-center"
        >
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{
                        beneficiariesPagination.from
                    }}</span>
                    to
                    <span class="font-medium">{{
                        beneficiariesPagination.to
                    }}</span>
                    of
                    <span class="font-medium">{{
                        beneficiariesPagination.total
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
                        v-for="link in beneficiariesPagination.links"
                        :key="link.label"
                        :disabled="!link.url"
                        @click="changeBeneficiariesPage(link.url)"
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

        <!-- No Beneficiaries Message -->
        <div
            v-if="
                !isBeneficiariesLoading &&
                selectedPayroll &&
                beneficiaries.length === 0
            "
            class="text-center py-8"
        >
            <p class="text-gray-500 text-lg">
                No beneficiaries found for this payroll.
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import { debounce } from "lodash-es";
import axios from "axios";

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
const beneficiariesPagination = ref(null);
const beneficiarySearch = ref("");
const beneficiariesPerPage = 10;

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
    fetchBeneficiaries(
        selectedPayroll.value.payrollID,
        1,
        beneficiarySearch.value
    );
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
        only: ["payrolls"],
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
        const response = await axios.get(
            `/payroll/${payrollId}/beneficiaries`,
            {
                params: { page, per_page: beneficiariesPerPage, search },
            }
        );
        beneficiaries.value = response.data.beneficiaries.data;
        beneficiariesPagination.value = {
            current_page: response.data.beneficiaries.current_page,
            last_page: response.data.beneficiaries.last_page,
            from: response.data.beneficiaries.from,
            to: response.data.beneficiaries.to,
            total: response.data.beneficiaries.total,
            links: response.data.beneficiaries.links,
        };
        selectedPayroll.value = response.data.payroll;
    } catch (err) {
        setError(
            "An error occurred while fetching beneficiaries. Please try again."
        );
        console.error(err);
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

const setAmount = async () => {
    if (!selectedPayroll.value || !beneficiaryAmount.value) return;

    try {
        isBeneficiariesLoading.value = true;
        await axios.post(
            `/payroll/${selectedPayroll.value.payrollID}/beneficiaries`,
            {
                amount: beneficiaryAmount.value,
                beneficiaries: beneficiaries.value.map((b) => ({
                    beneficiaryID: b.beneficiaryID,
                    status: b.status,
                })),
            }
        );
        // Update local beneficiary amounts
        beneficiaries.value = beneficiaries.value.map((b) => ({
            ...b,
            amount: beneficiaryAmount.value,
        }));
        await fetchBeneficiaries(selectedPayroll.value.payrollID);
    } catch (err) {
        setError(
            "An error occurred while updating beneficiary amounts. Please try again."
        );
    } finally {
        isBeneficiariesLoading.value = false;
    }
};

const updateBeneficiaryStatus = async (beneficiary) => {
    try {
        isBeneficiariesLoading.value = true;
        await axios.post(
            `/payroll/${selectedPayroll.value.payrollID}/beneficiaries`,
            {
                amount: beneficiary.amount,
                beneficiaries: [
                    {
                        beneficiaryID: beneficiary.beneficiaryID,
                        status: beneficiary.status,
                    },
                ],
            }
        );
        await fetchBeneficiaries(selectedPayroll.value.payrollID);
    } catch (err) {
        setError(
            "An error occurred while updating beneficiary status. Please try again."
        );
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
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

table {
    font-family: "Inter", sans-serif;
}
</style>
