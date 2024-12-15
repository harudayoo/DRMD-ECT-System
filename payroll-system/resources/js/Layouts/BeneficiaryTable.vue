<template>
  <div>
    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-gray-900"></div>
    </div>

    <!-- Error State -->
    <div
      v-else-if="error"
      class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
      role="alert"
    >
      <strong class="font-bold">Error!</strong>
      <span class="block sm:inline">{{ error }}</span>
    </div>

    <!-- Beneficiaries Table -->
    <div v-else class="overflow-x-auto bg-white rounded-2xl shadow mt-8">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-white">
          <tr>
            <th
              v-for="(header, index) in headers"
              :key="header"
              :class="getColumnClass(index)"
              class="px-5 py-4 text-left text-sm font-medium text-black uppercase tracking-wider"
            >
              {{ header }}
            </th>
            <th
              class="px-4 py-3 text-center text-sm font-medium text-black uppercase tracking-wider"
            >
              Action
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="beneficiary in paginatedBeneficiaries"
            :key="beneficiary.beneficiaryID"
            class="hover:bg-gray-100 cursor-pointer"
          >
            <td
              v-for="(key, index) in displayFields"
              :key="key"
              :class="getColumnClass(index)"
              class="px-4 py-2 whitespace-nowrap text-sm text-gray-900"
            >
              <template v-if="key === 'dateOfBirth'">
                {{ formatDate(beneficiary[key]) }}
              </template>
              <template v-else-if="key === 'status'">
                {{ getStatusText(beneficiary[key]) }}
              </template>
              <template v-else-if="key === 'extensionName'">
                {{ beneficiary[key] || "N/A" }}
              </template>
              <template v-else>
                {{ beneficiary[key] }}
              </template>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button
                class="bg-blue-900 hover:bg-blue-700 text-white font-bold py-2 px-4 ml-28 rounded"
                @click="$emit('edit-beneficiary', beneficiary)"
              >
                Edit
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- No Data State -->
    <div v-if="!loading && !error && beneficiaries.length === 0" class="text-center py-8">
      <p class="text-gray-500 text-lg">No Beneficiaries Found.</p>
    </div>

    <!-- Pagination -->
    <div
      v-if="!loading && !error && beneficiaries.length > 0"
      class="mt-4 flex justify-between items-center"
    >
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
            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            << Previous
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
            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next >>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";

const props = defineProps<{
  beneficiaries: Array<any>;
}>();

const loading = ref(true);
const error = ref<string | null>(null);

const headers = [
  "Last Name",
  "First Name",
  "Middle Name",
  "Extension Name",
  "Date of Birth",
  "Sex",
  "Status",
];

const displayFields = [
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
    "text-center w-[12%]", // Beneficiary Number
    "text-center w-[12%]", // Last Name
    "text-center w-[12%]", // First Name
    "text-center w-[12%]", // Middle Name
    "text-center w-[10%]", // Extension Name
    "text-center w-[12%]", // Date of Birth
    "text-center w-[6%]", // Sex
    "text-center w-[12%]", // Status
  ];
  return alignments[index] || "text-left";
};

// Pagination
const itemsPerPage = 6;
const currentPage = ref(1);

const totalPages = computed(() => Math.ceil(props.beneficiaries.length / itemsPerPage));

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

const goToPage = (page: number | string) => {
  if (typeof page === "number") {
    currentPage.value = page;
  }
};

const formatDate = (dateString: string) => {
  if (!dateString) return "N/A";
  // This will handle both ISO strings and regular date strings
  const date = new Date(dateString);
  return date.toISOString().split("T")[0];
};

const getStatusText = (status: number) => {
  const statusMap: { [key: number]: string } = {
    1: "Claimed",
    2: "Unclaimed",
    3: "Disqualified",
    4: "Double Entry",
    5: "Validated",
    // Add more status mappings as needed
  };
  return statusMap[status] || "Unknown";
};

// Simulating an API call to fetch beneficiaries
const fetchBeneficiaries = () => {
  return new Promise((resolve, reject) => {
    setTimeout(() => {
      if (Math.random() > 0.1) {
        // 90% success rate
        resolve(props.beneficiaries);
      } else {
        reject(new Error("Failed to fetch beneficiaries"));
      }
    }, 1000);
  });
};

onMounted(async () => {
  try {
    await fetchBeneficiaries();
    loading.value = false;
  } catch (e) {
    loading.value = false;
    error.value = (e as Error).message;
  }
});

defineEmits(["edit-beneficiary"]);
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

table {
  font-family: "Inter", sans-serif;
}
</style>
