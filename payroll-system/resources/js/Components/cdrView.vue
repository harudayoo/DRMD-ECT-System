<template>
    <div class="relative">
        <!-- Search Bar -->
        <div class="flex justify-end items-center mb-6">
            <div class="w-[32%]">
                <label for="search" class="sr-only">Search CDRs</label>
                <div class="relative">
                    <input
                        id="search"
                        v-model="search"
                        @input="debouncedSearch"
                        type="text"
                        placeholder="Search by CDR Name or CDR Number"
                        class="bg-white text-gray-700 rounded-full pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full h-9"
                    />
                    <div
                        class="absolute left-3 top-1/2 transform -translate-y-1/2"
                    >
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

        <!-- Error Message -->
        <div
            v-if="error"
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
            role="alert"
        >
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline"> {{ error }}</span>
            <button
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
            </button>
        </div>

        <!-- CDR Table -->
        <div
            v-if="!isLoading"
            class="bg-white shadow overflow-hidden sm:rounded-lg"
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
                                    sortField === header.key
                                        ? sortDirection === "asc"
                                            ? "↑"
                                            : "↓"
                                        : "↕"
                                }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="cdr in cdrs.data"
                        :key="cdr.cdrID"
                        class="hover:bg-gray-100 cursor-pointer"
                        @click="selectCDR(cdr)"
                    >
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                        >
                            {{ cdr.cdrID }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                        >
                            {{ cdr.rcdID }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                        >
                            {{ cdr.cdrName }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ formatDate(cdr.created_at) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- CDR Details Modal -->
            <div
                v-if="showDetailsModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
            >
                <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-4xl">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">CDR Details</h2>
                        <button
                            @click="closeDetailsModal"
                            class="text-gray-500 hover:text-gray-700"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <div
                        v-if="isLoadingDetails"
                        class="flex justify-center items-center py-12"
                    >
                        <div
                            class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
                        ></div>
                    </div>

                    <div v-else>
                        <!-- CDR Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">
                                CDR Information
                            </h3>
                            <div
                                class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg"
                            >
                                <div>
                                    <p class="text-sm text-gray-600">CDR ID</p>
                                    <p class="font-medium">
                                        {{ selectedCDR?.cdrID }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        CDR Name
                                    </p>
                                    <p class="font-medium">
                                        {{ selectedCDR?.cdrName }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- RCD Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">
                                RCD Information
                            </h3>
                            <div
                                v-if="rcdDetails"
                                class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg"
                            >
                                <div>
                                    <p class="text-sm text-gray-600">RCD ID</p>
                                    <p class="font-medium">
                                        {{ rcdDetails.rcdID }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        RCD Name
                                    </p>
                                    <p class="font-medium">
                                        {{ rcdDetails.rcdName }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Beneficiaries Table -->
                        <div>
                            <h3 class="text-lg font-semibold mb-2">
                                Beneficiaries
                            </h3>
                            <div
                                v-if="beneficiaries.length > 0"
                                class="overflow-x-auto"
                            >
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                            >
                                                Name
                                            </th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                            >
                                                Payroll ID
                                            </th>
                                            <!-- Add more columns as needed -->
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200"
                                    >
                                        <tr
                                            v-for="beneficiary in beneficiaries"
                                            :key="beneficiary.id"
                                        >
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                            >
                                                {{ beneficiary.name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                            >
                                                {{ beneficiary.payrollNumber }}
                                            </td>
                                            <!-- Add more columns as needed -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="text-center py-4 text-gray-500">
                                No beneficiaries found for this RCD.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-if="cdrs.data.length === 0"
                class="text-center py-8 text-gray-500"
            >
                No CDRs found. Try adjusting your search criteria.
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center py-12">
            <div
                class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"
            ></div>
        </div>

        <!-- Pagination -->
        <div
            v-if="cdrs.links && cdrs.links.length > 3"
            class="mt-6 flex justify-between items-center"
        >
            <div class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ cdrs.from }}</span>
                to
                <span class="font-medium">{{ cdrs.to }}</span>
                of
                <span class="font-medium">{{ cdrs.total }}</span>
                results
            </div>
            <nav
                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                aria-label="Pagination"
            >
                <button
                    v-for="(link, i) in cdrs.links"
                    :key="i"
                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                    :class="[
                        link.active
                            ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                        link.url
                            ? 'cursor-pointer'
                            : 'cursor-not-allowed opacity-50',
                    ]"
                    :disabled="!link.url"
                    @click="changePage(link.url)"
                    v-html="link.label"
                ></button>
            </nav>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import debounce from "lodash/debounce";

const props = defineProps({
    cdrs: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["new-cdr"]);

// State
const search = ref("");
const isLoading = ref(false);
const error = ref(null);
const sortField = ref("cdrID");
const sortDirection = ref("asc");

// Table headers configuration
const headers = [
    { key: "cdrID", label: "CDR ID", sortable: true },
    { key: "rcdID", label: "RCD ID", sortable: true },
    { key: "cdrName", label: "CDR Name", sortable: true },
    { key: "created_at", label: "Created At", sortable: true },
];

const form = useForm({
    search: "",
    sort_field: sortField.value,
    sort_direction: sortDirection.value,
});

// Methods
const performSearch = () => {
    isLoading.value = true;
    error.value = null;

    form.get(route("cdr.index"), {
        preserveState: true,
        preserveScroll: true,
        only: ["cdrs"],
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

const debouncedSearch = debounce(() => {
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

const changePage = (url) => {
    if (!url) return;

    isLoading.value = true;
    error.value = null;

    form.get(url, {
        preserveState: true,
        preserveScroll: true,
        only: ["cdrs"],
        onSuccess: () => {
            isLoading.value = false;
        },
        onError: () => {
            isLoading.value = false;
            error.value =
                "An error occurred while changing pages. Please try again.";
        },
    });
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

const dismissError = () => {
    error.value = null;
};

// Watchers
watch(search, (value) => {
    form.search = value;
    debouncedSearch();
});

// Lifecycle hooks
onMounted(() => {
    search.value = form.search;
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

table {
    font-family: "Inter", sans-serif;
}
</style>
