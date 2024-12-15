<template>
    <div class="h-screen flex flex-col overflow-hidden bg-gray-100">
        <!-- Top navigation bar -->
        <NavBar
        :show="isSidebarVisible"
            @toggle-sidebar="toggleSidebar"
            @toggle-dark-mode="toggleDarkMode"
        />

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <transition name="slide">
                <Sidebar
                    v-if="isSidebarVisible"
                    :is-open="isSidebarVisible"
                    @open-modal="openModal"
                />
            </transition>

            <!-- Main content -->
            <main
                class="flex-1 px-14 -mt-3 overflow-x-hidden overflow-y-auto bg-gray-100"
            >
                <div class="container mx-auto px-6 py-8">
                    <h3 class="text-gray-700 text-2xl font-medium">
                        ECT Payroll System Dashboard
                    </h3>

                    <!-- Payroll Stream Chart -->
                    <div class="mt-5">
                        <div class="bg-white p-6 rounded-2xl shadow-md">
                            <h4 class="text-xl font-semibold mb-4">
                                Payroll Stream
                            </h4>
                            <div class="h-48">
                                <canvas ref="chartRef"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Status Analytics and Davao Region tables -->
                    <div class="mt-6 flex space-x-4" style="max-height: 280px">
                        <!-- Status Analytics component -->
                        <div class="w-1/3">
                            <div
                                class="bg-white p-6 rounded-2xl shadow-md h-full"
                            >
                                <h4 class="text-xl font-semibold mb-2">
                                    Status Analytics
                                </h4>
                                <div
                                    v-for="(stat, index) in computedStatistics"
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
                                class="bg-white p-6 overflow-y-auto rounded-2xl shadow-md"
                                style="max-height: 265px"
                            >
                                <h4 class="text-xl font-semibold mb-2">
                                    Davao Region
                                </h4>
                                <table class="w-full">
                                    <thead class="top-0 bg-white">
                                        <tr>
                                            <th
                                                v-for="(
                                                    header, index
                                                ) in tableHeaders"
                                                :key="index"
                                                class="text-md font-medium text-black tracking-wider"
                                                :class="{
                                                    'w-1/4 text-left':
                                                        index === 0,
                                                    'w-1/4 text-center':
                                                        index === 1,
                                                    'w-1/4 text-right':
                                                        index === 2,
                                                }"
                                            >
                                                {{ header }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="row in tableData"
                                            :key="row.provinces"
                                            class="border-t border-gray-200 cursor-pointer hover:bg-gray-100"
                                            @click="
                                                navigateToMunicipality(
                                                    row.provinceID,
                                                    row.provinces
                                                )
                                            "
                                        >
                                            <td class="py-2">
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
                                            <td class="py-2 text-center">
                                                {{ row.beneficiaries }}
                                            </td>
                                            <td class="py-2 text-right">
                                                {{ row.amount }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div
                                    class="w-full flex justify-end pt-1 border-t -mb-5"
                                >
                                    <div
                                        class="w-1/4 flex justify-between items-center px-2 py-1.5"
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
import { router } from "@inertiajs/vue3";
import Chart from "chart.js/auto";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";

// Define Province interface
interface Province {
    provinceID: number;
    provinceName: string;
    claimed: number;
    unclaimed: number;
    disqualified: number;
    double_entry: number;
    totalBeneficiaries: number;
    totalAmountReleased: number;
}

// Define props with explicit type
const props = defineProps<{
    provinces: Province[];
}>();

// Sidebar functions
const isSidebarVisible = ref(true);

const toggleSidebar = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};

const isDarkMode = ref(false);

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
};

const openModal = () => {
    // Implement your modal opening logic here
};

// Use the provinces data from props
const provinces = ref<Province[]>(props.provinces);

const computedStatistics = computed(() => [
    {
        label: "Claimed",
        value: provinces.value
            .reduce((sum, province) => sum + province.claimed, 0)
            .toLocaleString(),
    },
    {
        label: "Unclaimed",
        value: provinces.value
            .reduce((sum, province) => sum + province.unclaimed, 0)
            .toLocaleString(),
    },
    {
        label: "Disqualified",
        value: provinces.value
            .reduce((sum, province) => sum + province.disqualified, 0)
            .toLocaleString(),
    },
    {
        label: "Duplicates",
        value: provinces.value
            .reduce((sum, province) => sum + province.double_entry, 0)
            .toLocaleString(),
    },
]);

const tableHeaders = ref(["Province", "Number of Beneficiary", "Amount"]);

const tableData = computed(() =>
    provinces.value.map((province) => ({
        provinceID: province.provinceID,
        provinces: province.provinceName,
        beneficiaries: province.totalBeneficiaries.toLocaleString(),
        amount: `₱ ${province.totalAmountReleased.toLocaleString()}`,
    }))
);

const totalAmount = computed((): string => {
    const total = provinces.value.reduce(
        (sum, province) => sum + Number(province.totalAmountReleased),
        0
    );
    return (
        "₱ " +
        total.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })
    );
});

// Chart configuration
const labels = computed(() =>
    provinces.value.map((province) => province.provinceName)
);

const data = computed(() => ({
    labels: labels.value,
    datasets: [
        {
            label: "Number of Beneficiaries",
            backgroundColor: "rgb(59, 68, 122)",
            data: provinces.value.map(
                (province) => province.totalBeneficiaries
            ),
        },
        {
            label: "Amount Released",
            backgroundColor: "rgb(166, 170, 238)",
            data: provinces.value.map(
                (province) => province.totalAmountReleased
            ),
        },
    ],
}));

const chartRef = ref<HTMLCanvasElement | null>(null);

onMounted(() => {
    if (chartRef.value) {
        new Chart(chartRef.value, {
            type: "bar",
            data: data.value,
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

const navigateToMunicipality = (provinceID: number, provinceName: string) => {
    router.visit(`/municipalities/${provinceID}`, {
        data: { provinceName: provinceName },
    });
};
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

/* Custom scroll bar for the table */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-thumb {
    background-color: #6b7280; /* Custom thumb color */
    border-radius: 10px; /* Rounded corners */
    border: 2px solid #e5e7eb; /* Border around the thumb */
}

::-webkit-scrollbar-track {
    background-color: #f3f4f6; /* Track color */
    border-radius: 10px; /* Rounded corners */
}

/* Scrollbar hover effect */
::-webkit-scrollbar-thumb:hover {
    background-color: #374151; /* Darker on hover */
}
</style>
