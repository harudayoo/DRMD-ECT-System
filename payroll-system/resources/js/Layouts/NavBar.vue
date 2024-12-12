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
            <div class="relative">
                <!-- User Icon -->
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

                <!-- Dropdown Menu -->
                <Transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <div
                        v-if="isMenuOpen"
                        class="absolute right-0 mt-2 w-64 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50"
                        style="top: 100%"
                    >
                        <!-- Profile Header -->
                        <div class="px-4 py-3 border-b">
                            <p class="text-sm font-medium text-gray-900">
                                John Doe
                            </p>
                            <p class="text-sm text-gray-600 truncate">
                                john.doe@example.com
                            </p>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1">
                            <a
                                href="userprofile"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                            >
                                <svg
                                    class="w-5 h-5 mr-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                                My Profile
                            </a>
                        </div>

                        <!-- Logout Section -->
                        <div class="border-t">
                            <button
                                @click="logout"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center"
                            >
                                <svg
                                    class="w-5 h-5 mr-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    />
                                </svg>
                                Sign Out
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
        <Calendar :show="showCalendar" @close="showCalendar = false" />
    </nav>
</template>

<script setup lang="ts">
import Calendar from "@/Components/Calendar.vue";
import { ref } from "vue";
import { defineProps, defineEmits } from "vue";
import axios, { AxiosError } from "axios";



const showCalendar = ref(false);
const isMenuOpen = ref(false);

const toggleCalendar = () => {
    showCalendar.value = !showCalendar.value;
};

const toggleUserMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
    console.log("User menu toggled:", isMenuOpen.value);
};

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["close", "toggle-sidebar"]);

const logout = async () => {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
        if (!csrfToken) {
            console.error("CSRF token not found");
            return;
        }

        await axios.post(
            "/logout",
            {},
            {
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
            }
        );

        window.location.href = "/login";
    } catch (error) {
        console.error("Logout failed:", error);

        // Use AxiosError type guard to access response
        if (axios.isAxiosError(error) && error.response && error.response.status === 401) {
            window.location.href = "/login";
        }
    }
};

// Update toggleSidebar function
const toggleSidebar = () => {
    emit("toggle-sidebar");
};
</script>
