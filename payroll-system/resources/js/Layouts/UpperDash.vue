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
                Province or Municipality
            </h3>
        </div>
        <div class="flex items-center">
            <button
                @click="$emit('toggle-graph')"
                class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 mr-4 focus:outline-none"
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
            <div class="relative">
                <input
                    type="text"
                    placeholder="Search..."
                    class="bg-white text-gray-900 dark:text-gray-100 rounded-full pl-10 pr-4 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 w-72 h-8 transition-all duration-300 ease-in-out"
                />
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="3"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-search text-gray-400"
                    >
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useRouter } from "vue-router";

defineProps<{
    showGraph: boolean;
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
