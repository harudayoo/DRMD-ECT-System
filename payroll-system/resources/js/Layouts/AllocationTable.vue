<template>
  <div class="w-full">
    <div
      class="bg-white p-6 overflow-y-auto rounded-2xl shadow-md"
      :style="{ maxHeight: tableMaxHeight }"
    >
      <h4 class="text-xl font-semibold mb-2">{{ tableTitle }}</h4>
      <table class="w-full">
        <thead class="sticky top-0 bg-white">
          <tr>
            <th
              v-for="(header, index) in tableHeaders"
              :key="index"
              class="text-md font-medium text-black tracking-wider"
              :class="{
                'w-1/4 text-left': index === 0,
                'w-1/4 text-center': index === 1,
                'w-1/4 text-right': index === 2,
              }"
            >
              {{ header }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="row in tableData"
            :key="row.id"
            class="border-t border-gray-200 cursor-pointer hover:bg-gray-100"
            @click="navigateTo(row.id)"
          >
            <td class="py-2">
              <div class="flex items-center justify-between">
                <span>{{ row.name }}</span>
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
      <div class="w-full flex justify-end pt-1 border-t -mb-5">
        <div class="w-1/4 flex justify-between items-center px-2 py-1.5">
          <span class="text-lg font-black">Total</span>
          <span class="text-md font-bold">{{ totalAmount }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  showGraph: Boolean,
  tableHeaders: Array,
  tableData: Array,
  tableTitle: {
    type: String,
    default: "Allocation Table",
  },
  parentType: {
    type: String,
    required: true,
    validator: (value: string) => ["province", "municipality"].includes(value),
  },
});

const tableMaxHeight = computed(() => {
  return props.showGraph ? "280px" : "calc(100vh - 300px)";
});

const totalAmount = computed(() => {
  if (!props.tableData) {
    return "₱ 0.00";
  }
  const total = props.tableData.reduce(
    (sum, row) => sum + parseFloat(row.amount.replace("₱ ", "").replace(",", "")),
    0
  );
  return `₱ ${total.toLocaleString(undefined, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  })}`;
});

const navigateTo = (id: number) => {
  if (props.parentType === "province") {
    router.visit(`/barangays/${id}`);
  } else if (props.parentType === "municipality") {
    router.visit(`/barangay/${id}`);
  }
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

body {
  font-family: "Inter", sans-serif;
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
