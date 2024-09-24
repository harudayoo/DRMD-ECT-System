<template>
    <div>
        <!-- Beneficiaries Table -->
        <div class="overflow-x-auto bg-white rounded-2xl shadow mt-6">
            <table class="min-w-[94%] divide-y divide-gray-200 mx-6">
                <thead class="bg-white">
                    <tr>
                        <th
                            v-for="(header, index) in headers"
                            :key="header"
                            :class="getColumnClass(index)"
                            class="pb-4 pt-4 text-md tracking-wider font-semibold text-black"
                        >
                            {{ header }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="beneficiary in paginatedBeneficiaries"
                        :key="beneficiary.beneficiaryNumber"
                        class="hover:bg-gray-50"
                    >
                        <td
                            v-for="(key, index) in displayFields"
                            :key="key"
                            :class="getColumnClass(index)"
                            class="py-4 whitespace-nowrap text-sm text-gray-900"
                        >
                            {{
                                key === "status"
                                    ? getStatusText(beneficiary[key])
                                    : beneficiary[key]
                            }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-700">
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
                <nav
                    class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                    aria-label="Pagination"
                >
                    <button
                        @click="previousPage"
                        :disabled="currentPage === 1"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-xl border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    >
                        <span class="sr-only">Previous</span>
                        <svg
                            class="h-5 w-5"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                    <button
                        v-for="page in visiblePageNumbers"
                        :key="page"
                        @click="goToPage(page)"
                        :class="[
                            page === currentPage
                                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                            { 'cursor-default': page === '...' },
                        ]"
                        :disabled="page === '...'"
                    >
                        {{ page }}
                    </button>
                    <button
                        @click="nextPage"
                        :disabled="currentPage === totalPages"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    >
                        <span class="sr-only">Next</span>
                        <svg
                            class="h-5 w-5"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";

const props = defineProps({
    beneficiaries: {
        type: Array as () => Array<any>,
        required: true,
        default: () => [],
    },
});

const headers = [
    "Beneficiary Number",
    "Last Name",
    "First Name",
    "Middle Name",
    "Extension Name",
    "Date of Birth",
    "Sex",
    "Status",
];

const displayFields = [
    "beneficiaryNumber",
    "lastName",
    "firstName",
    "middleName",
    "extensionName",
    "dateOfBirth",
    "sex",
    "status",
];

const getColumnClass = (index: number) => {
    const alignments = [
        "text-center w-[4%]",
        "text-center w-[8%]",
        "text-center w-[8%]",
        "text-center w-[8%]",
        "text-center w-[6%]",
        "text-center w-[8%]",
        "text-center w-[4%]",
        "text-center w-[8%]",
    ];
    return alignments[index] || "text-left";
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

// Pagination logic
const itemsPerPage = 8;
const currentPage = ref(1);

const totalPages = computed(() =>
    Math.ceil(props.beneficiaries.length / itemsPerPage)
);

const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() =>
    Math.min(startIndex.value + itemsPerPage, props.beneficiaries.length)
);

const paginatedBeneficiaries = computed(() => {
    return props.beneficiaries.slice(startIndex.value, endIndex.value);
});

const visiblePageNumbers = computed(() => {
    const totalPageCount = totalPages.value;
    const current = currentPage.value;
    const delta = 2;
    const range = [];
    const rangeWithDots = [];

    range.push(1);

    if (totalPageCount <= 7) {
        for (let i = 2; i < totalPageCount; i++) {
            range.push(i);
        }
    } else {
        for (let i = current - delta; i <= current + delta; i++) {
            if (i < totalPageCount && i > 1) {
                range.push(i);
            }
        }
    }

    range.push(totalPageCount);

    let prev = 0;
    for (const i of range) {
        if (prev) {
            if (i - prev === 2) {
                rangeWithDots.push(prev + 1);
            } else if (i - prev !== 1) {
                rangeWithDots.push("...");
            }
        }
        rangeWithDots.push(i);
        prev = i;
    }

    return rangeWithDots;
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
    font-family: "Inter", sans-serif;
}
</style>
