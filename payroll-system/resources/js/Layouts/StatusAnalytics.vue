<template>
  <div class="w-1/3">
    <div class="bg-white p-6 rounded-2xl shadow-md h-full">
      <h4 class="text-xl font-semibold mb-2">Status Analytics</h4>
      <div v-if="analytics">
        <div
          v-for="(stat, index) in computedStatistics"
          :key="index"
          class="flex justify-between items-center py-2 border-b border-gray-200"
        >
          <span>{{ stat.label }}</span>
          <span class="font-medium">{{ stat.value }}</span>
        </div>
      </div>
      <div v-else class="text-center text-gray-500 py-4">No data available</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps({
  analytics: {
    type: Object,
    required: true,
  },
});

const computedStatistics = computed(() => {
  if (!props.analytics) return [];

  return [
    { label: "Claimed", value: props.analytics.claimed.toLocaleString() },
    { label: "Unclaimed", value: props.analytics.unclaimed.toLocaleString() },
    { label: "Disqualified", value: props.analytics.disqualified.toLocaleString() },
    { label: "Duplicates", value: props.analytics.doubleEntry.toLocaleString() },
  ];
});
</script>
