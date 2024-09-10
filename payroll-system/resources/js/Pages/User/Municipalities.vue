<template>
    <div
        class="h-screen flex flex-col overflow-hidden bg-gray-100"
        :class="{ dark: isDarkMode }"
    >
        <!-- Top navigation bar -->
        <NavBar @toggle-sidebar="toggleSidebar" />

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <transition name="slide">
                <Sidebar v-if="isSidebarOpen" :is-open="isSidebarOpen" />
            </transition>

            <!-- Main content -->
            <main
                class="flex-1 px-14 -mt-1.5 overflow-x-hidden overflow-y-auto bg-gray-100"
            >
                <div class="container mx-auto px-6 py-8">
                    <!-- Upper Dashboard -->
                    <UpperDash
                        :show-graph="showGraph"
                        @toggle-graph="toggleGraph"
                    />

                    <!-- Payroll Stream Chart -->
                    <PayrollStream :show-graph="showGraph" />

                    <!-- Status Analytics and Allocation Table -->
                    <div class="mt-8 flex space-x-4">
                        <!-- Status Analytics component -->
                        <StatusAnalytics :statistics="statistics" />

                        <!-- Allocation Table component -->
                        <AllocationTable
                            :show-graph="showGraph"
                            :table-headers="tableHeaders"
                            :table-data="tableData"
                            :table-max-height="tableMaxHeight"
                        />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import UpperDash from "@/Layouts/UpperDash.vue";
import PayrollStream from "@/Layouts/PayrollStream.vue";
import StatusAnalytics from "@/Layouts/StatusAnalytics.vue";
import AllocationTable from "@/Layouts/AllocationTable.vue";

const isDarkMode = ref(false);
const isSidebarOpen = ref(true);
const showGraph = ref(true);

const statistics = ref([
    { label: "Total Beneficiaries", value: "15,000" },
    { label: "Total Amount", value: "₱75,000,000" },
    { label: "Municipality", value: "5" },
    { label: "Municipalities", value: "49" },
]);

const tableHeaders = ["Municipality", "Number of Beneficiaries", "Amount"];
const tableData = ref([
    {
        municipality: "Asuncion",
        beneficiaries: "5,000",
        amount: "₱ 25,000,000",
    },
    {
        municipality: "Braulio E. Dujali",
        beneficiaries: "4,000",
        amount: "₱ 20,000,000",
    },
    { municipality: "Carmen", beneficiaries: "3,000", amount: "₱ 15,000,000" },
    {
        municipality: "Kapalong",
        beneficiaries: "2,000",
        amount: "₱ 10,000,000",
    },
    {
        municipality: "New Corella",
        beneficiaries: "1,000",
        amount: "₱ 5,000,000",
    },
    { municipality: "Panabo", beneficiaries: "5,000", amount: "₱ 25,000,000" },
    { municipality: "Samal", beneficiaries: "4,000", amount: "₱ 20,000,000" },
    {
        municipality: "San Isidro",
        beneficiaries: "3,000",
        amount: "₱ 15,000,000",
    },
    {
        municipality: "Santo Tomas",
        beneficiaries: "2,000",
        amount: "₱ 10,000,000",
    },
    { municipality: "Tagum", beneficiaries: "1,000", amount: "₱ 5,000,000" },
    {
        municipality: "Talaingod",
        beneficiaries: "5,000",
        amount: "₱ 25,000,000",
    },
]);

const tableMaxHeight = computed(() => {
    return showGraph.value ? "280px" : "calc(100vh - 300px)";
});

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleGraph = () => {
    showGraph.value = !showGraph.value;
};
</script>

<style>
/* Styles remain unchanged */
</style>
