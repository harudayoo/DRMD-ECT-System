<template>
    <div class="h-screen flex flex-col overflow-hidden">
        <!-- Top bar -->
        <nav
            class="bg-sky-900 shadow-2xl flex items-center justify-between px-4 py-.5"
        >
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
                    class="text-white mt-1 focus:outline-none hover:text-red-700 transition-colors duration-200"
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
                    <img
                        src="/icons/main-logo.png"
                        alt="form-bg"
                        class="w-12 -mt-10"
                    />
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
                            <a href="#" class="hover:text-gray-500"
                                >Beneficiaries</a
                            >
                            <svg
                                :class="{
                                    'rotate-90':
                                        openSubMenu === 'beneficiaries',
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
                            <a
                                href="#"
                                class="block text-2xl hover:text-gray-400"
                                >Add</a
                            >
                            <a
                                href="#"
                                class="block text-2xl hover:text-gray-400"
                                >Edit</a
                            >
                        </div>
                    </li>
                    <li class="mb-4 text-2xl">
                        <div
                            @click="toggleSubMenu('masterlist')"
                            class="flex justify-between items-center cursor-pointer"
                        >
                            <a href="#" class="hover:text-gray-500"
                                >Masterlist</a
                            >
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
                        <div
                            v-show="openSubMenu === 'masterlist'"
                            class="mt-2 ml-4 rounded-md p-2"
                        >
                            <a
                                href="#"
                                class="block text-2xl hover:text-gray-400"
                                >Edit</a
                            >
                            <a
                                href="#"
                                class="block text-2xl hover:text-gray-400"
                                >Import</a
                            >
                            <a
                                href="#"
                                class="block text-2xl hover:text-gray-400"
                                >Export</a
                            >
                            <a
                                href="#"
                                class="block text-2xl hover:text-gray-400"
                                >Archive</a
                            >
                        </div>
                    </li>
                    <li class="mb-4 text-2xl">
                        <div
                            @click="toggleSubMenu('documents')"
                            class="flex justify-between items-center cursor-pointer"
                        >
                            <a href="#" class="hover:text-gray-500"
                                >Documents</a
                            >
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
                        <div
                            v-show="openSubMenu === 'documents'"
                            class="mt-2 ml-4 rounded-md p-2"
                        >
                            <a
                                href="#"
                                class="block text-2xl hover:text-gray-400"
                                >Payroll Form</a
                            >
                            <a
                                href="#"
                                class="block text-2xl hover:text-gray-400"
                                >Cash Report</a
                            >
                            <a
                                href="#"
                                class="block text-2xl hover:text-gray-400"
                                >RCD</a
                            >
                            <a
                                href="#"
                                class="block text-2xl hover:text-gray-400"
                                >CDR</a
                            >
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="relative">
            <div
                class="absolute right-3 mt-3 flex items-center bg-white rounded-full shadow-md"
                style="width: 270px"
            >
                <input
                    type="text"
                    placeholder="Search..."
                    class="w-full py-1.5 text-sm pr-10 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-700 placeholder-gray-400"
                />
                <button
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors duration-200"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center">
                <div class="ml-1 mt-1 mx-auto py-2 px-4 flex items-center">
                    <button
                        @click="goBack"
                        class="m-4 -ml-1 hover:text-gray-600 transition-colors duration-200"
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
                                stroke-width="3"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            ></path>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-2xl mt-6 font-black text-black">
                            Davao del Norte
                        </h1>
                        <h2 class="text-lg -mt-1 font-medium">
                            Number of Beneficiaries and Assistance Provided per
                            Municipality
                        </h2>
                    </div>
                </div>
            </header>
            <!-- Chart and statistics -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Main Bar Chart -->
                <div class="flex-1 flex justify-center items-center -mt-16">
                    <div style="width: 80%; height: 70%">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
                <div class="flex mx-4 mb-4 h-2/6">
                    <!-- Statistics -->
                    <div
                        class="text-black shadow-2xl -mt-14 text-lg rounded-xl w-1/4 p-2"
                    >
                        <h2
                            class="bg-rose-600 text-white py-1 rounded-xl font-medium text-center mb-2"
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
                    <div
                        class="flex-1 border shadow-2xl rounded-xl p-2 -mt-14 ml-2 flex flex-col"
                    >
                        <table class="w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        v-for="(header, index) in tableHeaders"
                                        :key="index"
                                        class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider bg-stone-100"
                                        :class="{
                                            'w-1/5 text-left rounded-tl-xl':
                                                index === 0,
                                            'w-3/5 text-center': index === 1,
                                            'w-1/4 text-right rounded-tr-xl':
                                                index ===
                                                tableHeaders.length - 1,
                                        }"
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
                        </table>
                        <div
                            class="overflow-y-auto flex-grow"
                            style="max-height: 180px"
                        >
                            <table class="w-full divide-y divide-gray-200">
                                <tbody
                                    class="bg-white divide-y text-lg font-medium divide-gray-200"
                                >
                                    <tr
                                        v-for="row in tableData"
                                        :key="row.municipality"
                                    >
                                        <td
                                            class="px-2 py-1 whitespace-nowrap w-1/5"
                                        >
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span
                                                    class="truncate mr-2"
                                                    style="
                                                        max-width: calc(
                                                            100% - 24px
                                                        );
                                                    "
                                                >
                                                    {{ row.municipality }}
                                                </span>
                                                <a
                                                    :href="
                                                        '/municipality/' +
                                                        row.municipality
                                                            .toLowerCase()
                                                            .replace(/ /g, '-')
                                                    "
                                                    class="text-black hover:text-gray-300 flex-shrink-0"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                        <td
                                            class="px-2 py-1 whitespace-nowrap w-3/5 text-center"
                                        >
                                            {{ row.beneficiaries }}
                                        </td>
                                        <td
                                            class="px-2 py-1 whitespace-nowrap w-1/4 text-right"
                                        >
                                            {{ row.amount }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="w-full flex justify-end pt-1 border-t">
                            <div
                                class="w-1/4 flex justify-between items-center px-2 py-1"
                            >
                                <span class="text-xl font-black">Total</span>
                                <span class="text-xl font-bold">{{
                                    totalAmount
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import Chart, { ChartConfiguration, ChartItem } from "chart.js/auto";

// Menu logic
const isMenuOpen = ref(false);
const isUserMenuOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value;
};

const openSubMenu = ref<string | null>(null);

const toggleSubMenu = (menu: string) => {
    if (openSubMenu.value === menu) {
        openSubMenu.value = null;
    } else {
        openSubMenu.value = menu;
    }
};

const logout = () => {
    console.log("Logout clicked");
    // Add your logout logic here
    window.location.href = "/"; // This will redirect to the root URL
};

const goBack = () => {
    console.log("Go back clicked");
    window.history.back();
};

// Data
const statistics = ref([
    { label: "Claimed", value: "1,523" },
    { label: "Unclaimed", value: "789" },
    { label: "Disqualified", value: "1,219" },
    { label: "Duplicates", value: "121" },
]);

const tableHeaders = ref(["Municipality", "Number of Beneficiary", "Amount"]);

const tableData = ref([
    { municipality: "Asuncion", beneficiaries: "1,245", amount: "102,673" },
    {
        municipality: "Braulio E. Dujali",
        beneficiaries: "1,045",
        amount: "207,135",
    },
    { municipality: "Carmen", beneficiaries: "3,764", amount: "245,235" },
    { municipality: "Kapalong", beneficiaries: "2,983", amount: "215,064" },
    { municipality: "New Corella", beneficiaries: "1,870", amount: "211,233" },
    { municipality: "Panabo", beneficiaries: "3,764", amount: "245,235" },
    { municipality: "Samal", beneficiaries: "2,983", amount: "215,064" },
    { municipality: "San Isidro", beneficiaries: "1,870", amount: "211,233" },
    { municipality: "Santo Tomas", beneficiaries: "3,764", amount: "245,235" },
    { municipality: "Tagum", beneficiaries: "2,983", amount: "215,064" },
    { municipality: "Talaingod", beneficiaries: "1,870", amount: "211,233" },
]);

const totalAmount = computed(() => {
    return tableData.value
        .reduce((sum, row) => sum + parseInt(row.amount.replace(",", "")), 0)
        .toLocaleString();
});

// Chart data and configuration
const labels = [
    "Asuncion",
    "Braulio E. Dujali",
    "Carmen",
    "Kapalong",
    "New Corella",
    "Panabo",
    "Samal",
    "San Isidro",
    "Santo Tomas",
    "Tagum",
    "Talaingod",
];

const data = {
    labels: labels,
    datasets: [
        {
            label: "Number of Beneficiaries",
            backgroundColor: "rgb(59, 68, 122)",
            data: [
                134335, 632476, 135351, 624663, 124351, 153513, 635621, 135245,
                614511, 624611, 753466,
            ],
        },
        {
            label: "Amount Released",
            backgroundColor: "rgb(166, 170, 238)",
            data: [
                735212, 246421, 375346, 142764, 743753, 235426, 135325, 624576,
                135356, 757624, 864853,
            ],
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

.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #000000;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #000000;
}
</style>
