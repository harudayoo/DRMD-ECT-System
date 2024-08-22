<template>
    <div class="h-screen flex flex-col overflow-hidden">
        <!-- Top bar -->
        <nav
            class="bg-sky-900 shadow-2xl flex items-center justify-between px-4 py-.5"
        >
            <!-- Menu Toggle Button -->
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

            <!-- User Menu Toggle Button -->
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
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
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
                    >
                        Log out
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <div
            class="fixed top-0 left-0 h-full w-80 bg-gray-800 bg-opacity-90 text-white transform transition-transform duration-300 ease-in-out z-50 overflow-y-auto"
            :class="{
                'translate-x-0': isMenuOpen,
                '-translate-x-full': !isMenuOpen,
            }"
        >
            <div class="p-4 relative">
                <!-- Logo and Title -->
                <span class="text-2xl font-semibold ml-14">
                    DSWD - DRMD
                    <img
                        src="/icons/main-logo.png"
                        alt="form-bg"
                        class="w-12 -mt-10"
                    />
                </span>
                <!-- Close Menu Button -->
                <button
                    @click="toggleMenu"
                    class="absolute top-2.5 right-4 text-white focus:outline-none hover:text-red-700 transition-colors duration-200"
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

                <!-- Navigation Links -->
                <ul class="mt-4 ml-4 font-thin">
                    <!-- Beneficiaries Submenu -->
                    <li class="mb-4 text-xl">
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
                                href="/add-beneficiary"
                                class="block text-xl hover:text-gray-400"
                                >Add</a
                            >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Edit</a
                            >
                        </div>
                    </li>

                    <!-- Masterlist Submenu -->
                    <li class="mb-4 text-xl">
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
                            class="mt-2 ml-4 rounded-md p-1"
                        >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Import</a
                            >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Export</a
                            >
                        </div>
                    </li>

                    <!-- Documents Submenu -->
                    <li class="mb-4 text-xl">
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
                            class="mt-2 ml-4 rounded-md p-1"
                        >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Payroll Form</a
                            >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Cash Report</a
                            >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >RCD</a
                            >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >CDR</a
                            >
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header>
                <div class="ml-10 mx-auto py-2 px-4">
                    <h1 class="text-2xl font-black text-black">
                        ECT Payroll System Dashboard
                    </h1>
                </div>
            </header>

            <!-- Chart and Statistics -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Main Bar Chart -->
                <div class="flex-1 flex justify-center items-center -mt-16">
                    <div style="width: 80%; height: 70%">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>

                <!-- Statistics and Table -->
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
                            class="flex justify-between items-center font-medium py-1 px-2 border-t border-white"
                        >
                            <span>Claimed:</span>
                            <span class="font-medium">{{
                                totalProvinces
                            }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center font-medium py-1 px-2 border-t border-white"
                        >
                            <span>Unclaimed:</span>
                            <span class="font-medium">{{
                                totalBeneficiaries
                            }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center font-medium py-1 px-2 border-t border-b border-white"
                        >
                            <span>Disqualified:</span>
                            <span class="font-medium">{{
                                totalAmountReleased
                            }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center font-medium py-1 px-2 border-t border-b border-white"
                        >
                            <span>Duplicates:</span>
                            <span class="font-medium">{{
                                totalAmountReleased
                            }}</span>
                        </div>
                    </div>

                    <!-- Table -->
                    <div
                        class="flex-1 border shadow-2xl rounded-xl p-2 -mt-14 ml-2 flex flex-col"
                    >
                        <table class="w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider bg-stone-100 w-1/3 text-left rounded-tl-xl"
                                    >
                                        Province Name
                                    </th>
                                    <th
                                        class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider bg-stone-100 w-1/3 text-center"
                                    >
                                        Total Beneficiaries
                                    </th>
                                    <th
                                        class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider bg-stone-100 w-1/3 text-right rounded-tr-xl"
                                    >
                                        Total Amount Released
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
                                        v-for="province in provinces"
                                        :key="province.provinceID"
                                        @click="
                                            goToMunicipalities(
                                                province.provinceID
                                            )
                                        "
                                        class="cursor-pointer hover:bg-gray-200"
                                    >
                                        <td
                                            class="px-2 py-1 whitespace-nowrap w-1/3"
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
                                                    {{ province.provinceName }}
                                                </span>
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-black hover:text-gray-300 flex-shrink-0"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </div>
                                        </td>
                                        <td
                                            class="px-2 py-1 whitespace-nowrap w-1/3 text-center"
                                        >
                                            {{ province.totalBeneficiaries }}
                                        </td>
                                        <td
                                            class="px-2 py-1 whitespace-nowrap w-1/3 text-right"
                                        >
                                            ₱{{
                                                parseFloat(
                                                    province.totalAmountReleased
                                                ).toFixed(2)
                                            }}
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
import Chart from "chart.js/auto";
import axios from "axios";

// Props
const props = defineProps({
    provinces: Array,
});

// Menu logic
const isMenuOpen = ref(false);
const isUserMenuOpen = ref(false);
const openSubMenu = ref<string | null>(null);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value;
};

const toggleSubMenu = (menu: string) => {
    if (openSubMenu.value === menu) {
        openSubMenu.value = null;
    } else {
        openSubMenu.value = menu;
    }
};

const logout = async () => {
    try {
        await axios.post(route("logout"));
        window.location.href = route("login");
    } catch (error) {
        console.error("Logout failed:", error);
    }
};

const goToMunicipalities = (provinceID: number) => {
    window.location.href = `/municipalities/${provinceID}`;
};
// Computed properties
const totalProvinces = computed(() => props.provinces.length);
const totalBeneficiaries = computed(() =>
    props.provinces.reduce(
        (sum, province) => sum + province.totalBeneficiaries,
        0
    )
);
const totalAmountReleased = computed(() =>
    props.provinces
        .reduce(
            (sum, province) => sum + parseFloat(province.totalAmountReleased),
            0
        )
        .toFixed(2)
);

const totalAmount = computed(() => {
    return props.provinces
        .reduce(
            (sum, province) => sum + parseFloat(province.totalAmountReleased),
            0
        )
        .toLocaleString("en-PH", { style: "currency", currency: "PHP" });
});

// Chart
onMounted(() => {
    const ctx = document.getElementById("myChart") as HTMLCanvasElement;
    if (ctx) {
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: props.provinces.map(
                    (province) => province.provinceName
                ),
                datasets: [
                    {
                        label: "Number of Beneficiaries",
                        backgroundColor: "rgb(59, 68, 122)",
                        data: props.provinces.map(
                            (province) => province.totalBeneficiaries
                        ),
                    },
                    {
                        label: "Amount Released",
                        backgroundColor: "rgb(166, 170, 238)",
                        data: props.provinces.map((province) =>
                            parseFloat(province.totalAmountReleased)
                        ),
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    }
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
