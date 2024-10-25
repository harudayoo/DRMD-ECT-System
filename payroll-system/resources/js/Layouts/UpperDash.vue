<template>
    <div class="flex justify-between items-center -mt-1.5 -mb-1">
        <div class="flex items-center">
            <button
                @click="goBack"
                class="mr-4 text-gray-600 hover:text-gray-400 focus:outline-none"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-arrow-left"
                >
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
            </button>
            <h3 class="text-gray-700 text-2xl font-medium">
                {{ title }}
            </h3>
        </div>
        <div class="-mr-4">
            <button
                @click="$emit('toggle-graph')"
                class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-300 mr-4 focus:outline-none"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-bar-chart-2"
                >
                    <line x1="18" y1="20" x2="18" y2="10" />
                    <line x1="12" y1="20" x2="12" y2="4" />
                    <line x1="6" y1="20" x2="6" y2="14" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useRouter } from "vue-router";

defineProps<{
    showGraph: boolean;
    title: string;
}>();

defineEmits<{
    (e: "toggle-graph"): void;
}>();

const router = useRouter();

const goBack = () => {
    if (window.history.length > 1) {
        window.history.back();
    }
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
