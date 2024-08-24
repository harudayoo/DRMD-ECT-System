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
                        <h1 class="text-2xl font-black text-black">
                            {{ provinceName }}
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
                            class="flex justify-between items-center font-medium py-1 px-2 border-t border-white"
                        >
                            <span>Claimed:</span>
                            <span class="font-medium">{{ totalClaimed }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center font-medium py-1 px-2 border-t border-white"
                        >
                            <span>Unclaimed:</span>
                            <span class="font-medium">{{
                                totalUnclaimed
                            }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center font-medium py-1 px-2 border-t border-b border-white"
                        >
                            <span>Disqualified:</span>
                            <span class="font-medium">{{
                                totalDisqualified
                            }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center font-medium py-1 px-2 border-t border-b border-white"
                        >
                            <span>Duplicates:</span>
                            <span class="font-medium">{{
                                totalDoubleEntry
                            }}</span>
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
                                        v-for="municipality in municipalities"
                                        :key="municipality.municipalityID"
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
                                                    {{
                                                        municipality.municipalityName
                                                    }}
                                                </span>
                                                <a
                                                    :href="`/barangays/${municipality.municipalityID}`"
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
                                            {{
                                                municipality.totalBeneficiaries
                                            }}
                                        </td>
                                        <td
                                            class="px-2 py-1 whitespace-nowrap w-1/4 text-right"
                                        >
                                            ₱{{
                                                parseFloat(
                                                    municipality.totalAmountReleased
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

const openSubMenu = ref<string | null>(null);

const toggleSubMenu = (menu: string) => {
    openSubMenu.value = openSubMenu.value === menu ? null : menu;
};

const logout = async () => {
    try {
        await axios.post(route("logout"));
        window.location.href = route("login");
    } catch (error) {
        console.error("Logout failed:", error);
    }
};

const goBack = () => {
    console.log("Go back clicked");
    window.history.back();
};

// Props
const props = defineProps({
    municipalities: {
        type: Array,
        required: true,
    },
    provinceID: {
        type: [Number, String],
        required: true,
    },
    provinceName: {
        type: String,
        default: "",
    },
});

// Computed properties for Status Analytics
const totalClaimed = computed(() =>
    props.municipalities.reduce(
        (sum, municipality) => sum + municipality.claimed,
        0
    )
);

const totalUnclaimed = computed(() =>
    props.municipalities.reduce(
        (sum, municipality) => sum + municipality.unclaimed,
        0
    )
);

const totalDisqualified = computed(() =>
    props.municipalities.reduce(
        (sum, municipality) => sum + municipality.disqualified,
        0
    )
);

const totalDoubleEntry = computed(() =>
    props.municipalities.reduce(
        (sum, municipality) => sum + municipality.double_entry,
        0
    )
);

const tableHeaders = ref(["Municipality", "Number of Beneficiary", "Amount"]);

const totalAmount = computed(() => {
    return props.municipalities
        .reduce(
            (sum, municipality) =>
                sum + parseFloat(municipality.totalAmountReleased),
            0
        )
        .toLocaleString("en-PH", { style: "currency", currency: "PHP" });
});

// Chart data and configuration
const labels = computed(() =>
    props.municipalities.map((m) => m.municipalityName)
);

const data = computed(() => ({
    labels: labels.value,
    datasets: [
        {
            label: "Number of Beneficiaries",
            backgroundColor: "rgb(59, 68, 122)",
            data: props.municipalities.map((m) => m.totalBeneficiaries),
        },
        {
            label: "Amount Released",
            backgroundColor: "rgb(166, 170, 238)",
            data: props.municipalities.map((m) =>
                parseFloat(m.totalAmountReleased)
            ),
        },
    ],
}));

const config: ChartConfiguration = {
    type: "bar",
    data: data.value,
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
