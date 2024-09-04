<template>
    <div class="h-screen flex flex-col overflow-hidden bg-gray-100">
        <!-- Top navigation bar -->
        <NavBar @toggle-sidebar="toggleSidebar" @click="toggleDarkMode" />

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <transition name="slide">
                <Sidebar
                    v-if="isSidebarOpen"
                    :is-open="isSidebarOpen"
                    @open-modal="openModal"
                />
            </transition>

            <!-- Main content -->
            <main
                class="flex-1 -mt-5 overflow-x-hidden overflow-y-auto bg-gray-100"
            >
                <div class="container mx-auto px-14 py-8">
                    <h3 class="text-gray-700 text-2xl font-medium">
                        ECT Payroll System Dashboard
                    </h3>

                    <!-- Payroll Stream Chart -->
                    <div class="mt-4">
                        <div class="bg-white p-6 rounded-2xl shadow-md">
                            <h4 class="text-xl font-semibold mb-4">
                                Payroll Stream
                            </h4>
                            <div class="h-44">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Status Analytics and Davao Region tables side by side -->
                    <div class="mt-6 flex space-x-4" style="height: 290px">
                        <!-- Status Analytics component -->
                        <div class="w-1/3">
                            <div
                                class="bg-white p-6 rounded-2xl shadow-md h-full"
                            >
                                <h4 class="text-xl font-semibold mb-2">
                                    Status Analytics
                                </h4>
                                <div
                                    v-for="(stat, index) in statistics"
                                    :key="index"
                                    class="flex justify-between items-center py-2 border-b border-gray-200"
                                >
                                    <span>{{ stat.label }}</span>
                                    <span class="font-medium">{{
                                        stat.value
                                    }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Allocation Table component -->
                        <div class="w-full">
                            <div
                                class="bg-white p-6 rounded-2xl shadow-md flex flex-col"
                                style="height: 290px"
                            >
                                <h4 class="text-xl font-semibold mb-2">
                                    Davao Region
                                </h4>
                                <div class="flex-grow overflow-hidden">
                                    <table class="w-full">
                                        <thead class="sticky top-0 bg-white">
                                            <tr>
                                                <th
                                                    v-for="(
                                                        header, index
                                                    ) in tableHeaders"
                                                    :key="index"
                                                    class="text-md font-medium text-black tracking-wider py-2"
                                                    :class="{
                                                        'w-1/5 text-left':
                                                            index === 0,
                                                        'w-2/6 text-center':
                                                            index === 1,
                                                        'w-1/5 text-right':
                                                            index === 2,
                                                    }"
                                                >
                                                    {{ header }}
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div
                                        class="overflow-y-auto"
                                        style="max-height: 160px"
                                    >
                                        <table class="w-full">
                                            <tbody>
                                                <tr
                                                    v-for="row in tableData"
                                                    :key="row.provinces"
                                                    class="border-t border-gray-200 cursor-pointer hover:bg-gray-100"
                                                    @click="
                                                        navigateToMunicipality(
                                                            row.provinces
                                                        )
                                                    "
                                                >
                                                    <td class="py-2 w-2/5">
                                                        <div
                                                            class="flex items-center justify-between"
                                                        >
                                                            <span>{{
                                                                row.provinces
                                                            }}</span>
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-5 w-5 mr-32 text-gray-400 flex-shrink-0"
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
                                                        class="py-2 text-center w-1/5"
                                                    >
                                                        {{ row.beneficiaries }}
                                                    </td>
                                                    <td
                                                        class="py-2 text-right w-2/5"
                                                    >
                                                        {{ row.amount }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div
                                    class="w-full flex justify-end pt-1 border-t mt-2"
                                >
                                    <div
                                        class="w-2/5 flex justify-between items-center px-2 -py-2 -mb-4"
                                    >
                                        <span class="text-lg font-black"
                                            >Total</span
                                        >
                                        <span class="text-md font-bold">{{
                                            totalAmount
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import Chart from "chart.js/auto";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";

// Sidebar functions
const isSidebarOpen = ref(true);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const isDarkMode = ref(false); // Track dark mode state

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
};

const openModal = () => {
    // Implement your modal opening logic here
};

// Dashboard data
const statistics = ref([
    { label: "Claimed", value: "1,523" },
    { label: "Unclaimed", value: "789" },
    { label: "Disqualified", value: "1,219" },
    { label: "Duplicates", value: "121" },
]);

const tableHeaders = ref(["Province", "Number of Beneficiary", "Amount"]);

const tableData = ref([
    { provinces: "Davao de Oro", beneficiaries: "1,245", amount: "₱ 102,673" },
    {
        provinces: "Davao Oriental",
        beneficiaries: "1,045",
        amount: "₱ 207,135",
    },
    {
        provinces: "Davao Occidental",
        beneficiaries: "3,764",
        amount: "₱ 245,235",
    },
    { provinces: "Davao del Sur", beneficiaries: "2,983", amount: "₱ 215,064" },
    {
        provinces: "Davao del Norte",
        beneficiaries: "1,870",
        amount: "₱ 211,233",
    },
]);

const totalAmount = computed(() => {
    const total = tableData.value.reduce((sum, row) => {
        const amount = parseFloat(
            row.amount.replace("₱", "").replace(/,/g, "")
        );
        return sum + amount;
    }, 0);
    return `₱ ${total.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })}`;
});

// Chart configuration
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
            data: [350521, 303566, 207897, 227991, 351564],
        },
        {
            label: "Amount Released",
            backgroundColor: "rgb(166, 170, 238)",
            data: [171624, 188943, 345234, 415853, 510427],
        },
    ],
};

onMounted(() => {
    const ctx = document.getElementById("myChart") as HTMLCanvasElement;
    if (ctx) {
        new Chart(ctx, {
            type: "bar",
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "bottom",
                    },
                },
            },
        });
    }
});
const router = useRouter();

const navigateToMunicipality = (municipality) => {
    const formattedMunicipality = municipality.toLowerCase().replace(/ /g, "-");
    router.push(`/municipality/${formattedMunicipality}`);
};

onMounted(() => {
    // Chart initialization code (omitted for brevity)
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

body {
    font-family: "Inter", sans-serif;
}

.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(-100%);
}
</style>
