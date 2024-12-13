<template>
  <div
    class="h-screen flex flex-col overflow-hidden bg-gray-100"
    :class="{ dark: isDarkMode }"
  >
    <!-- Top navigation bar -->
    <NavBar 
    :show="isSidebarVisible"
    @toggle-sidebar="toggleSidebar" />

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <transition name="slide">
        <Sidebar v-if="isSidebarVisible" :is-open="isSidebarVisible" />
      </transition>

      <!-- Main content -->
      <main class="flex-1 px-14 -mt-1.5 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="container mx-auto px-6 py-8">
          <UpperDash
            :show-graph="showGraph"
            @toggle-graph="toggleGraph"
            :title="provinceName"
          />

          <PayrollStream :show-graph="showGraph" :municipalities="municipalitiesData" />

          <!-- Status Analytics and Allocation Table -->
          <div class="mt-6 flex space-x-4">
            <!-- Updated StatusAnalytics component -->
            <StatusAnalytics :analytics="computedStatusAnalytics" />

            <!-- Allocation Table component -->
            <AllocationTable
              :show-graph="showGraph"
              :table-headers="tableHeaders"
              :table-data="computedTableData"
              :table-max-height="tableMaxHeight"
              :parent-type="'province'"
              table-title="Municipalities"
              @row-click="navigateToBarangay"
            />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import StatusAnalytics from "@/Layouts/StatusAnalytics.vue";
import AllocationTable from "@/Layouts/AllocationTable.vue";
import UpperDash from "@/Layouts/UpperDash.vue";
import PayrollStream from "@/Layouts/PayrollStream.vue";

interface Municipality {
  municipalityID: number;
  municipalityName: string;
  totalBeneficiaries: number;
  totalAmountReleased: number;
  claimed: number;
  unclaimed: number;
  disqualified: number;
  double_entry: number;
}

const props = defineProps<{
  municipalities: Municipality[];
  showGraph: boolean;
  provinceName: string;
}>();

const municipalitiesData = computed(() =>
  props.municipalities.map((municipality) => ({
    municipalityName: municipality.municipalityName,
    totalBeneficiaries: municipality.totalBeneficiaries,
    totalAmountReleased: municipality.totalAmountReleased,
  }))
);

const summaryData = computed(() => ({
  claimed: props.municipalities.reduce((sum, mun) => sum + mun.claimed, 0),
  unclaimed: props.municipalities.reduce((sum, mun) => sum + mun.unclaimed, 0),
  disqualified: props.municipalities.reduce((sum, mun) => sum + mun.disqualified, 0),
  doubleEntry: props.municipalities.reduce((sum, mun) => sum + mun.double_entry, 0),
}));

const municipalityTableData = computed(() =>
  props.municipalities.map((municipality) => ({
    id: municipality.municipalityID,
    name: municipality.municipalityName,
    beneficiaries: municipality.totalBeneficiaries.toLocaleString(),
    amount: `₱ ${municipality.totalAmountReleased.toLocaleString()}`,
  }))
);

const navigateToBarangay = (
  municipalityID: number | string,
  municipalityName: string
) => {
  router.visit(`/barangays/${municipalityID}`, {
    data: { municipalityName },
  });
};

const isDarkMode = ref(false);
const isSidebarVisible = ref(true);
const showGraph = ref(true);

const computedStatusAnalytics = computed(() => ({
  claimed: props.municipalities.reduce((sum, mun) => sum + mun.claimed, 0),
  unclaimed: props.municipalities.reduce((sum, mun) => sum + mun.unclaimed, 0),
  disqualified: props.municipalities.reduce((sum, mun) => sum + mun.disqualified, 0),
  doubleEntry: props.municipalities.reduce((sum, mun) => sum + mun.double_entry, 0),
}));

const tableHeaders = ["Municipality", "Number of Beneficiaries", "Amount"];

const computedTableData = computed(() =>
  props.municipalities.map((municipality) => ({
    id: municipality.municipalityID,
    name: municipality.municipalityName,
    beneficiaries: municipality.totalBeneficiaries,
    amount: `₱ ${municipality.totalAmountReleased.toLocaleString()}`,
  }))
);

const tableMaxHeight = computed(() => {
  return showGraph.value ? "280px" : "calc(100vh - 300px)";
});

const toggleSidebar = () => {
  isSidebarVisible.value = !isSidebarVisible.value;
};

const toggleGraph = () => {
  showGraph.value = !showGraph.value;
};
</script>

<style scoped>
/* Add any scoped styles here if needed */
</style>
