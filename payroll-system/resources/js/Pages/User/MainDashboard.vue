<template>
  <div class="h-screen flex flex-col overflow-hidden">
    <!-- Top bar -->
    <nav class="bg-sky-900 shadow-2xl flex items-center justify-between px-4 py-.5">
      <button
        @click="toggleMenu"
        class="text-white focus:outline-none hover:text-red-700 transition-colors duration-200"
      >
        <svg
          class="w-6 h-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2.5"
            d="M4 6h16M4 12h16M4 18h16"
          ></path>
        </svg>
      </button>
      <div class="relative">
        <button
          @click="toggleUserMenu"
          class="text-white mt-1.5 focus:outline-none hover:text-red-700 transition-colors duration-200"
        >
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
        </button>
        <div
          v-if="isUserMenuOpen"
          class="absolute right-0 w-48 bg-white rounded-md shadow-lg py-1 z-10"
        >
          <a
            href="#"
            @click="logout"
            class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-300"
            >Log out</a
          >
        </div>
      </div>
    </nav>

    <!-- Sidebar -->
    <div
      class="fixed top-0 left-0 h-full w-96 bg-gray-800 bg-opacity-90 text-white transform transition-transform duration-300 ease-in-out z-50 overflow-y-auto"
      :class="{
        'translate-x-0': isMenuOpen,
        '-translate-x-full': !isMenuOpen,
      }"
    >
      <div class="p-4">
        <span class="text-3xl font-semibold ml-14"
          >DSWD - DRMD
          <img src="/icons/main-logo.png" alt="form-bg" class="w-12 -mt-10" />
        </span>
        <button
          @click="toggleMenu"
          class="absolute top-1.5 right-4 text-white focus:outline-none hover:text-red-700 transition-colors duration-200"
        >
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2.5"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
        </button>
        <ul class="mt-6 ml-4 font-thin">
          <li class="mb-4 text-2xl">
            <div
              @click="toggleSubMenu('beneficiaries')"
              class="flex justify-between items-center cursor-pointer"
            >
              <a href="#" class="hover:text-gray-500">Beneficiaries</a>
              <svg
                :class="{
                  'rotate-90': openSubMenu === 'beneficiaries',
                }"
                class="w-5 h-5 text-white transition-transform duration-200"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="3"
                  d="M9 5l7 7-7 7"
                ></path>
              </svg>
            </div>
            <div
              v-show="openSubMenu === 'beneficiaries'"
              class="mt-2 ml-4 rounded-md p-2"
            >
              <a href="#" class="block text-2xl hover:text-gray-400">Add</a>
              <a href="#" class="block text-2xl hover:text-gray-400">Edit</a>
            </div>
          </li>
          <li class="mb-4 text-2xl">
            <div
              @click="toggleSubMenu('masterlist')"
              class="flex justify-between items-center cursor-pointer"
            >
              <a href="#" class="hover:text-gray-500">Masterlist</a>
              <svg
                :class="{
                  'rotate-90': openSubMenu === 'masterlist',
                }"
                class="w-5 h-5 text-white transition-transform duration-200"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="3"
                  d="M9 5l7 7-7 7"
                ></path>
              </svg>
            </div>
            <div v-show="openSubMenu === 'masterlist'" class="mt-2 ml-4 rounded-md p-2">
              <a href="#" class="block text-2xl hover:text-gray-400">Edit</a>
              <a href="#" class="block text-2xl hover:text-gray-400">Import</a>
              <a href="#" class="block text-2xl hover:text-gray-400">Export</a>
              <a href="#" class="block text-2xl hover:text-gray-400">Archive</a>
            </div>
          </li>
          <li class="mb-4 text-2xl">
            <div
              @click="toggleSubMenu('documents')"
              class="flex justify-between items-center cursor-pointer"
            >
              <a href="#" class="hover:text-gray-500">Documents</a>
              <svg
                :class="{
                  'rotate-90': openSubMenu === 'documents',
                }"
                class="w-5 h-5 text-white transition-transform duration-200"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="3"
                  d="M9 5l7 7-7 7"
                ></path>
              </svg>
            </div>
            <div v-show="openSubMenu === 'documents'" class="mt-2 ml-4 rounded-md p-2">
              <a href="#" class="block text-2xl hover:text-gray-400">Payroll Form</a>
              <a href="#" class="block text-2xl hover:text-gray-400">Cash Report</a>
              <a href="#" class="block text-2xl hover:text-gray-400">RCD</a>
              <a href="#" class="block text-2xl hover:text-gray-400">CDR</a>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <header class="">
        <div class="ml-10 mx-auto py-2 px-4">
          <h1 class="text-2xl font-black text-black">ECT Payroll System Dashboard</h1>
        </div>
      </header>

      <!-- Chart and statistics -->
      <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Main Bar Chart -->
        <div class="flex-1 flex justify-center items-center -mt-24">
          <div style="width: 80%; height: 65%">
            <canvas id="myChart"></canvas>
          </div>
        </div>
        <div class="flex h-2/6 mx-4 mb-4 -mt-4">
          <!-- Statistics -->
          <div class="text-black text-xl -mt-16 shadow-2xl rounded-xl w-1/4 p-2">
            <h2
              class="bg-rose-600 rounded-xl text-white py-1 font-medium text-center mb-2"
            >
              Status Analytics
            </h2>
            <div
              v-for="(stat, index) in statistics"
              :key="index"
              class="flex justify-between items-center font-medium py-1 px-2 border-t border-white"
              :class="{
                'border-b': index === statistics.length - 1,
              }"
            >
              <span>{{ stat.label }}</span>
              <span class="font-medium">{{ stat.value }}</span>
            </div>
          </div>

          <!-- Main Content -->
          <div class="flex-1 border -mt-16 shadow-xl rounded-xl ml-2 p-2">
            <div class="overflow-hidden rounded-xl">
              <table class="w-full divide-y divide-gray-200">
                <thead>
                  <tr>
                    <th
                      v-for="(header, index) in tableHeaders"
                      :key="index"
                      :class="[
                        'px-2 py-1 text-lg font-medium text-black uppercase tracking-wider bg-stone-100',
                        index === 0 ? 'text-left w-1/3' : '',
                        index === 1 ? 'text-center w-1/3' : '',
                        index === 2 ? 'text-right w-1/3' : '',
                      ]"
                    >
                      <div
                        class="h-full flex items-center"
                        :class="{
                          'justify-start': index === 0,
                          'justify-center': index === 1,
                          'justify-end': index === 2,
                        }"
                      >
                        {{ header }}
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y text-lg font-medium divide-gray-200">
                  <tr v-for="row in tableData" :key="row.provinces">
                    <td class="px-2 py-1 whitespace-nowrap w-1/3">
                      <Link
                        :href="route(row.routeName)"
                        class="flex justify-between items-center text-black hover:text-slate-500 cursor-pointer"
                      >
                        <span>{{ row.provinces }}</span>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-5 w-5 mr-44"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </Link>
                    </td>
                    <td class="px-2 py-1 whitespace-nowrap w-1/3 text-center">
                      {{ row.beneficiaries }}
                    </td>
                    <td class="px-2 py-1 whitespace-nowrap w-1/3 text-right">
                      {{ row.amount }}
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td class="whitespace-nowrap" colspan="2"></td>
                    <td class="px-2 py-2 whitespace-nowrap text-xl font-bold text-right">
                      <span class="mr-20 text-2xl">Total</span>
                      {{ totalAmount }}
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import Chart, { ChartConfiguration, ChartItem } from "chart.js/auto";
import axios from "axios";

// Menu logic
const isMenuOpen = ref(false);
const isUserMenuOpen = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value;
};

const logout = async () => {
  console.log("Logout clicked");

  try {
    // Send a request to the logout endpoint to invalidate the session
    await axios.post("/logout"); // Adjust the endpoint according to your backend configuration

    // Redirect to the home page or login page
    window.location.href = "/";
  } catch (error) {
    console.error("Logout failed:", error);
    // Handle the error (e.g., show an error message to the user)
  }
};

const openSubMenu = ref<string | null>(null);

const toggleSubMenu = (menu: string) => {
  if (openSubMenu.value === menu) {
    openSubMenu.value = null;
  } else {
    openSubMenu.value = menu;
  }
};

// Data
const statistics = ref([
  { label: "Claimed", value: "1,523" },
  { label: "Unclaimed", value: "789" },
  { label: "Disqualified", value: "1,219" },
  { label: "Duplicates", value: "121" },
]);

const tableHeaders = ref(["Province", "Number of Beneficiary", "Amount"]);

const tableData = ref([
  {
    provinces: "Davao de Oro",
    beneficiaries: "1,245",
    amount: "102,673",
    routeName: "oro.dashboard",
  },
  {
    provinces: "Davao Oriental",
    beneficiaries: "1,045",
    amount: "207,135",
    routeName: "oriental.dashboard",
  },
  {
    provinces: "Davao Occidental",
    beneficiaries: "3,764",
    amount: "245,235",
    routeName: "occidental.dashboard",
  },
  {
    provinces: "Davao del Sur",
    beneficiaries: "2,983",
    amount: "215,064",
    routeName: "sur.dashboard",
  },
  {
    provinces: "Davao del Norte",
    beneficiaries: "1,870",
    amount: "211,233",
    routeName: "norte.dashboard",
  },
]);

const totalAmount = computed(() => {
  return tableData.value
    .reduce((sum, row) => sum + parseInt(row.amount.replace(",", "")), 0)
    .toLocaleString();
});

// Chart data and configuration
const labels = [
  "Davao de Oro",
  "Davao Oriental",
  "Davao Occidental",
  "Davao del Sur",
  "Davao del Norte",
];

const data = {
  labels: labels,
  datasets: [
    {
      label: "Number of Beneficiaries",
      backgroundColor: "rgb(59, 68, 122)",
      data: [456456, 303566, 207897, 227991, 351564],
    },
    {
      label: "Amount Released",
      backgroundColor: "rgb(166, 170, 238)",
      data: [171624, 188943, 345234, 415853, 510427],
    },
  ],
};

const config: ChartConfiguration = {
  type: "bar",
  data: data,
  options: {
    responsive: true,
    maintainAspectRatio: false,
  },
};

onMounted(() => {
  const canvasTag = document.getElementById("myChart") as ChartItem;
  if (canvasTag) {
    new Chart(canvasTag, config);
  }
});

defineOptions({
  name: "TopBar",
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");

.font-inter {
  font-family: "Inter", sans-serif;
}
</style>
