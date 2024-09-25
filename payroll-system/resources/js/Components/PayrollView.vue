<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Search Bar -->
    <div class="mb-6">
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
    <div v-if="!isLoading" class="bg-white shadow overflow-hidden sm:rounded-lg">
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
            class="hover:bg-gray-100"
          >
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ payroll.payrollNumber }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ payroll.payrollName }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ payroll.municipalityName }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ payroll.barangayName }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatCurrency(payroll.subTotal) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(payroll.created_at) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Loading Indicator -->
    <div v-else class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>

    <!-- No Results Message -->
    <div v-if="!isLoading && payrolls.data.length === 0" class="text-center py-8">
      <p class="text-gray-500 text-lg">No payrolls found.</p>
    </div>

    <!-- Pagination -->
    <div
      v-if="!isLoading && !error && payrolls.data.length > 0"
      class="mt-6 flex justify-between items-center"
    >
      <div>
        <p class="text-sm text-gray-700">
          Showing
          <span class="font-medium">{{ payrolls.from }}</span>
          to
          <span class="font-medium">{{ payrolls.to }}</span>
          of
          <span class="font-medium">{{ payrolls.total }}</span>
          results
        </p>
      </div>
      <div>
        <nav
          class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <template v-for="(link, index) in filteredLinks" :key="index">
            <Link
              v-if="link.url"
              :href="link.url"
              v-html="link.label"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
              :class="{
                'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': link.active,
              }"
              @click.prevent="changePage(link.url)"
            />
            <span
              v-else
              v-html="link.label"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-gray-100 text-sm font-medium text-gray-500 cursor-not-allowed"
            />
          </template>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import { debounce } from "lodash-es";

const props = defineProps({
  payrolls: Object,
});

const search = ref("");
const isLoading = ref(false);
const error = ref(null);

const headers = [
  "Payroll Number",
  "Payroll Name",
  "Municipality",
  "Barangay",
  "Total Amount",
  "Date Created",
];

const form = useForm({
  search: "",
});

const debouncedSearch = debounce(() => {
  performSearch();
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
      error.value = "An error occurred while changing pages. Please try again.";
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
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

table {
  font-family: "Inter", sans-serif;
}
</style>
