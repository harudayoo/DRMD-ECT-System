<template>
    <div class="w-1/3">
        <div class="bg-white p-6 rounded-2xl shadow-md h-full">
            <h4 class="text-xl font-semibold mb-2">Status Analytics</h4>
            <div
                v-for="(stat, index) in computedStatistics"
                :key="index"
                class="flex justify-between items-center py-2 border-b border-gray-200"
            >
                <span>{{ stat.label }}</span>
                <span class="font-medium">{{ stat.value }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    provinces: Array<{
        claimed: number;
        unclaimed: number;
        disqualified: number;
        double_entry: number;
    }>;
}>();

const computedStatistics = computed(() => [
    {
        label: "Claimed",
        value: props.provinces
            .reduce((sum, province) => sum + province.claimed, 0)
            .toString(),
    },
    {
        label: "Unclaimed",
        value: props.provinces
            .reduce((sum, province) => sum + province.unclaimed, 0)
            .toString(),
    },
    {
        label: "Disqualified",
        value: props.provinces
            .reduce((sum, province) => sum + province.disqualified, 0)
            .toString(),
    },
    {
        label: "Double Entry",
        value: props.provinces
            .reduce((sum, province) => sum + province.double_entry, 0)
            .toString(),
    },
]);
</script>
