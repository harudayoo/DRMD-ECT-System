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
                    <UpperDash
                        :show-graph="showGraph"
                        @toggle-graph="toggleGraph"
                        :title="municipalityName"
                        :municipality-id="municipalityID"
                        :municipality-name="municipalityName"
                    />

                    <PayrollStream
                        :show-graph="showGraph"
                        :municipalities="barangays"
                    />

                    <!-- Status Analytics and Allocation Table -->
                    <div class="mt-6 flex space-x-4">
                        <!-- Updated StatusAnalytics component -->
                        <StatusAnalytics :analytics="statusAnalytics" />

                        <!-- Allocation Table component -->
                        <AllocationTable
                            :show-graph="showGraph"
                            :table-headers="tableHeaders"
                            :table-data="computedTableData"
                            :table-max-height="tableMaxHeight"
                            @row-click="navigateToBarangayMasterlist"
                            parent-type="municipality"
                        />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import StatusAnalytics from "@/Layouts/StatusAnalytics.vue";
import AllocationTable from "@/Layouts/AllocationTable.vue";
import UpperDash from "@/Layouts/UpperDash.vue";
import PayrollStream from "@/Layouts/PayrollStream.vue";

const props = defineProps({
    barangays: {
        type: Array,
        required: true,
    },
    municipalityID: {
        type: Number,
        required: true,
    },
    municipalityName: {
        type: String,
        required: true,
    },
    statusAnalytics: {
        type: Object,
        required: true,
    },
});

const isDarkMode = ref(false);
const isSidebarOpen = ref(true);
const showGraph = ref(true);

const tableHeaders = ["Barangay", "Number of Beneficiaries", "Amount"];

const computedTableData = computed(() =>
    props.barangays.map((barangay) => ({
        id: barangay.barangayID,
        name: barangay.barangayName,
        beneficiaries: barangay.totalBeneficiaries.toLocaleString(),
        amount: `₱ ${barangay.totalAmountReleased.toLocaleString()}`,
    }))
);

const tableMaxHeight = computed(() => "calc(100vh - 300px)");

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleGraph = () => {
    showGraph.value = !showGraph.value;
};

const navigateToBarangayMasterlist = (barangayId, barangayName) => {
    router.visit(
        route("barangay.masterlist", {
            barangayID: barangayId,
            barangayName: barangayName,
        })
    );
};
</script>
