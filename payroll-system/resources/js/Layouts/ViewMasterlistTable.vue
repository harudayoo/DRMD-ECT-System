<template>
  <div>
    <!-- Masterlist Table -->
    <div class="overflow-x-auto bg-white rounded-2xl shadow mt-6">
      <table class="min-w-[96%] divide-y divide-gray-200 mx-4">
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
            v-for="masterlist in paginatedMasterlists"
            :key="masterlist.masterlistID"
            class="hover:bg-gray-50"
          >
            <td
              v-for="(key, index) in displayFields"
              :key="key"
              :class="getColumnClass(index)"
              class="py-4 whitespace-nowrap text-sm text-gray-900"
            ></td>
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
          <span class="font-medium">{{ masterlists.length }}</span>
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
            v-for="page in totalPages"
            :key="page"
            @click="goToPage(page)"
            :class="[
              currentPage === page
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600 '
                : 'bg-white  border-gray-300 text-gray-500  hover:bg-gray-50 ',
              'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
            ]"
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
  masterlists: {
    type: Array as () => Array<any>,
    required: true,
    default: () => [],
  },
});

const headers = [
  "masterlist ID",
  "Barangay",
  "List Label",
  "Number of Beneficiary",
  "Amount",
];

const displayFields = [
  "masterlistID",
  "barangayID",
  "masterlistName",
  "totalBeneficiaries",
  "totalAmountReleased",
];

const getColumnClass = (index: number) => {
  const alignments = [
    "text-center w-[10%]",
    "text-center w-[10%]",
    "text-center w-[10%]",
    "text-center w-[10%]",
    "text-center w-[8%]",
    "text-center w-[10%]",
    "text-center w-[8%]",
    "text-left w-[15%]",
    "text-center w-[10%]",
    "text-center w-[9%]",
  ];
  return alignments[index] || "text-left";
};

// Pagination logic
const itemsPerPage = 12;
const currentPage = ref(1);

const totalPages = computed(() => Math.ceil(props.masterlists.length / itemsPerPage));

const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const endIndex = computed(() =>
  Math.min(startIndex.value + itemsPerPage, props.masterlists.length)
);

const paginatedMasterlists = computed(() => {
  return props.masterlists.slice(startIndex.value, endIndex.value);
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
