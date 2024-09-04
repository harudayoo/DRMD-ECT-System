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
                @click="toggleDarkMode"
                class="text-gray-600 dark:text-gray-300 focus:outline-none mx-2 transition-colors duration-200"
            >
                <svg
                    v-if="!isDarkMode"
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
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                    ></path>
                </svg>
                <svg
                    v-else
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
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                    ></path>
                </svg>
            </button>
            <button
                @click="toggleCalendar"
                class="text-gray-600 focus:outline-none mx-2"
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
                @click="toggleNotifications"
                class="text-gray-600 focus:outline-none mx-2"
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
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                    ></path>
                </svg>
            </button>
            <button
                @click="toggleUserMenu"
                class="text-gray-600 focus:outline-none ml-2"
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
    </nav>
</template>

<script setup lang="ts">
import { ref } from "vue";

const isDarkMode = ref(false);
const isCalendarOpen = ref(false);
const isNotificationsOpen = ref(false);
const isUserMenuOpen = ref(false);

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    if (isDarkMode.value) {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }
};

const toggleCalendar = () => {
    isCalendarOpen.value = !isCalendarOpen.value;
    console.log("Calendar toggled:", isCalendarOpen.value);
};

const toggleNotifications = () => {
    isNotificationsOpen.value = !isNotificationsOpen.value;
    console.log("Notifications toggled:", isNotificationsOpen.value);
};

const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value;
    console.log("User menu toggled:", isUserMenuOpen.value);
};

// Add emit
const emit = defineEmits(["toggle-sidebar"]);

// Update toggleSidebar function
const toggleSidebar = () => {
    emit("toggle-sidebar");
};
</script>
