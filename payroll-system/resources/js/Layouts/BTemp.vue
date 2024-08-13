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
                style="width: 350px"
            >
                <input
                    type="text"
                    placeholder="Search..."
                    class="w-full py-1.5 text-base pr-10 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-700 placeholder-gray-400"
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
            <!-- Header -->
            <header class="flex items-center">
                <div class="ml-1 -mb-3 mx-auto px-4 flex items-center">
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
                        <h1 class="text-2xl font-black text-black">
                            {{ pageTitle }}
                        </h1>
                    </div>
                </div>
            </header>
            <!-- Chart and statistics -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <div class="flex mx-4 mb-4 mt-3 h-full">
                    <!-- Statistics -->
                    <div
                        class="text-black text-base shadow-2xl rounded-xl w-1/4 p-2"
                    >
                        <h2
                            class="bg-rose-600 text-white py-1 rounded-xl font-medium text-2xl text-center mb-2"
                        >
                            Status Analytics
                        </h2>
                        <div
                            v-for="(stat, index) in statistics"
                            :key="index"
                            class="flex justify-between items-center font-medium text-2xl py-10 px-4 border-t border-white"
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
                        class="flex-1 shadow-2xl rounded-xl p-2 ml-2 flex flex-col"
                    >
                        <div class="rounded-t-xl overflow-hidden">
                            <table class="w-full table-fixed">
                                <thead class="bg-stone-100">
                                    <tr>
                                        <th
                                            class="w-2/5 px-2 py-1 text-2xl font-medium text-black uppercase tracking-wider text-left"
                                        >
                                            {{ tableHeaders[0] }}
                                        </th>
                                        <th
                                            class="w-1/2 px-2 py-1 text-2xl font-medium text-black uppercase tracking-wider text-center"
                                        >
                                            {{ tableHeaders[1] }}
                                        </th>
                                        <th
                                            class="w-2/5 px-2 py-1 text-2xl font-medium text-black uppercase tracking-wider text-right"
                                        >
                                            {{ tableHeaders[2] }}
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div
                            class="overflow-y-auto flex-1"
                            style="max-height: calc(100vh - 220px)"
                        >
                            <table class="w-full table-fixed">
                                <tbody
                                    class="bg-white divide-y text-xl font-medium divide-gray-200"
                                >
                                    <tr
                                        v-for="row in tableData"
                                        :key="row.name"
                                    >
                                        <td
                                            class="w-2/5 px-2 py-3 whitespace-nowrap"
                                        >
                                            <div
                                                class="flex justify-between items-center"
                                            >
                                                <span>{{ row.name }}</span>
                                                <a
                                                    :href="
                                                        '/barangay/' +
                                                        row.name
                                                            .toLowerCase()
                                                            .replace(/ /g, '-')
                                                    "
                                                    class="text-black hover:text-gray-300 w-3/5"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-7 w-7"
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
                                            class="w-1/5 px-2 py-1 whitespace-nowrap text-center"
                                        >
                                            {{ row.beneficiaries }}
                                        </td>
                                        <td
                                            class="w-2/5 px-2 py-1 whitespace-nowrap text-right"
                                        >
                                            {{ row.amount }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div
                            class="border-t border-gray-200 w-full flex justify-end pt-2"
                        >
                            <div
                                class="w-1/5 flex justify-between items-center px-2 py-2"
                            >
                                <span class="text-2xl font-bold">Total</span>
                                <span class="text-2xl font-bold">{{
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
import { ref, computed } from "vue";

// Props
interface Props {
    pageTitle: string;
    statistics: Array<{ label: string; value: string }>;
    tableHeaders: Array<string>;
    tableData: Array<{ name: string; beneficiaries: string; amount: string }>;
}

const props = withDefaults(defineProps<Props>(), {
    pageTitle: "Dashboard",
    statistics: () => [
        { label: "Claimed", value: "0" },
        { label: "Unclaimed", value: "0" },
        { label: "Disqualified", value: "0" },
        { label: "Duplicates", value: "0" },
    ],
    tableHeaders: () => ["Name", "Number of Beneficiary", "Amount"],
    tableData: () => [],
});

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

const totalAmount = computed(() => {
    return props.tableData
        .reduce((sum, row) => sum + parseInt(row.amount.replace(",", "")), 0)
        .toLocaleString();
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
