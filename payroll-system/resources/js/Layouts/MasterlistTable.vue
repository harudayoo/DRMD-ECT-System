<template>
    <div>
        <!-- Beneficiaries Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-2xl shadow mt-8">
        <table class="min-w-[96%] divide-y divide-gray-200 mx-7 dark:divide-gray-700">
            <thead class="bg-white dark:bg-gray-700">
            <tr>
                <th v-for="(header, index) in headers" :key="header" :class="getColumnClass(index)" class="pb-5 pt-7 text-lg tracking-wider font-semibold text-black dark:text-gray-300">
                {{ header }}
                </th>
            </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="beneficiary in paginatedBeneficiaries" :key="beneficiary.beneficiaryNumber" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td v-for="(value, key, index) in beneficiary" :key="key" :class="getColumnClass(index)" class="py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                {{ value }}
                </td>
            </tr>
            </tbody>
        </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-700 dark:text-gray-300">
            Showing
            <span class="font-medium">{{ startIndex + 1 }}</span>
            to
            <span class="font-medium">{{ endIndex }}</span>
            of
            <span class="font-medium">{{ beneficiaries.length }}</span>
            results
            </p>
        </div>
        <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <button
                @click="previousPage"
                :disabled="currentPage === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-xl border border-gray-300 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
                <span class="sr-only">Previous</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            <button
                v-for="page in totalPages"
                :key="page"
                @click="goToPage(page)"
                :class="[
                currentPage === page ? 'z-10 bg-indigo-50 dark:bg-indigo-900 border-indigo-500 text-indigo-600 dark:text-indigo-300' : 'bg-white dark:bg-gray-800 border-gray-300 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700',
                'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                ]"
            >
                {{ page }}
            </button>
            <button
                @click="nextPage"
                :disabled="currentPage === totalPages"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
            >
                <span class="sr-only">Next</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            </nav>
        </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

const props = defineProps<{
    beneficiaries: Array<any>;
}>();

const headers = [
    'Beneficiary Number',
    'Last Name',
    'First Name',
    'Middle Name',
    'Extension Name',
    'Date of Birth',
    'Status'
];

const getColumnClass = (index: number) => {
    const alignments = [
        'text-center w-[15%]',    // Beneficiary Number
        'text-center w-[15%]',    // Last Name
        'text-center w-[15%]',    // First Name
        'text-center',    // Middle Name
        'text-center',  // Extension Name
        'text-center',  // Date of Birth
        'text-center'    // Status
    ];
    return alignments[index] || 'text-left';
};

// Pagination
const itemsPerPage = 12;
const currentPage = ref(1);

const totalPages = computed(() => Math.ceil(props.beneficiaries.length / itemsPerPage));

const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, props.beneficiaries.length));

const paginatedBeneficiaries = computed(() => {
    return props.beneficiaries.slice(startIndex.value, endIndex.value);
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
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

table {
    font-family: 'Inter', sans-serif;
}
</style>
