<template>
    <div class="w-1/3">
        <div class="bg-white p-6 rounded-2xl shadow-md h-full">
            <h4 class="text-xl font-semibold mb-2">Status Analytics</h4>
            <div
                v-for="(stat, index) in statistics"
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
defineProps<{
    statistics: { label: string; value: string }[];
}>();
</script>
