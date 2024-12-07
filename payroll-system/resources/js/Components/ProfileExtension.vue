<template>
    <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95"
    >
        <div
            v-if="show"
            class="absolute right-0 mt-2 w-64 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50"
            style="top: 100%"
        >
            <!-- Profile Header -->
            <div class="px-4 py-3 border-b">
                <p class="text-sm font-medium text-gray-900">John Doe</p>
                <p class="text-sm text-gray-600 truncate">
                    john.doe@example.com
                </p>
            </div>

            <!-- Menu Items -->
            <div class="py-1">
                <button
                    @click="goToProfile"
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
                </button>
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
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import axios from "axios";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["close"]);

const logout = async () => {
    try {
        await axios.post(
            "/logout",
            {},
            {
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            }
        );
        window.location.href = "/login";
    } catch (error) {
        console.error("Logout failed:", error);
        if (error.response && error.response.status === 401) {
            window.location.href = "/login";
        }
    }
};
</script>
