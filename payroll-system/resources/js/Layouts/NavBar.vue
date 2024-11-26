<!-- NavBar.vue -->
<template>
    <nav
        class="bg-white shadow-sm flex items-center justify-between px-4 py-2 border-b border-stone-300"
    >
        <div class="flex items-center">
            <button
                @click="toggleSidebar"
                class="text-gray-600 focus:outline-none mr-4"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    ></path>
                </svg>
            </button>
            <div class="flex items-center">
                <img
                    src="/icons/main-logo.png"
                    alt="DRMD Logo"
                    class="h-8 mr-2"
                />
                <span class="text-xl font-semibold text-gray-800">DRMD</span>
            </div>
        </div>
        <div class="flex items-center">
            <button
                @click="toggleCalendar"
                class="relative text-gray-600 focus:outline-none mx-2 p-2 rounded-full hover:text-blue-900 hover:bg-gray-300 transition-all duration-200"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    ></path>
                </svg>
            </button>
            <button
                @click="toggleUserMenu"
                class="relative text-gray-600 focus:outline-none mx-2 p-2 rounded-full hover:text-blue-900 hover:bg-gray-300 transition-all duration-200"
            >
                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
            </button>
        </div>
        <Calendar :show="showCalendar" @close="showCalendar = false" />
    </nav>
</template>

<script setup lang="ts">
import Calendar from "@/Components/Calendar.vue";
import { ref } from "vue";

const showCalendar = ref(false);
const isMenuOpen = ref(false);

const toggleCalendar = () => {
    showCalendar.value = !showCalendar.value;
};

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
    console.log("User menu toggled:", isMenuOpen.value);
};

// Add emit
const emit = defineEmits(["toggle-sidebar"]);

// Update toggleSidebar function
const toggleSidebar = () => {
    emit("toggle-sidebar");
};
</script>
