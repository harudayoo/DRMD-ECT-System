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
      <main class="flex-1 px-14 -mt-1.5 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="container mx-auto px-6 py-8">
          <h2 class="text-2xl font-semibold mb-4">{{ municipalityName }} Barangays</h2>

          <!-- Status Analytics and Allocation Table -->
          <div class="mt-8 flex space-x-4">
            <!-- Updated StatusAnalytics component -->
            <StatusAnalytics :analytics="statusAnalytics" />

            <!-- Allocation Table component -->
            <AllocationTable
              :show-graph="showGraph"
              :table-headers="tableHeaders"
              :table-data="computedTableData"
              :table-max-height="tableMaxHeight"
              :parent-type="'municipality'"
              table-title="Barangays"
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
import StatusAnalytics from "@/Layouts/StatusAnalytics.vue";
import AllocationTable from "@/Layouts/AllocationTable.vue";

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
const showGraph = ref(false);

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
</script>
