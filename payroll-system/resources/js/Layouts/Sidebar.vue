<template>
    <aside
        v-if="isOpen"
        class="bg-white w-64 flex-shrink-0 border-r overflow-y-auto rounded-tr-xl rounded-br-lg shadow-lg flex flex-col h-full"
    >
        <nav class="mt-5 flex-grow">
            <h3
                class="px-6 text-xs font-semibold text-gray-600 uppercase tracking-wide"
            >
                Home
            </h3>
            <a
                href="#"
                class="flex items-center px-6 py-2 text-gray-700 hover:bg-gray-100"
            >
                <svg
                    class="w-5 h-5 mr-3"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    ></path>
                </svg>
                Dashboard
            </a>
            <div class="mt-4">
                <h3
                    class="px-6 text-xs font-semibold text-gray-600 uppercase tracking-wide"
                >
                    Main Menu
                </h3>

                <!-- Beneficiaries Submenu -->
                <div class="mt-2">
                    <button
                        @click="toggleSubMenu('beneficiaries')"
                        class="flex items-center justify-between w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
                    >
                        <span class="flex items-center">
                            <svg
                                class="w-5 h-5 mr-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                ></path>
                            </svg>
                            Beneficiaries
                        </span>
                        <svg
                            :class="{
                                'transform rotate-180':
                                    openSubMenu === 'beneficiaries',
                            }"
                            class="w-4 h-4 transition-transform duration-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            ></path>
                        </svg>
                    </button>
                    <div
                        v-show="openSubMenu === 'beneficiaries'"
                        class="mt-2 py-2 bg-gray-50"
                    >
                        <a
                            @click="$emit('open-modal')"
                            href="#"
                            class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                            >Add</a
                        >
                        <a
                            href="#"
                            class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                            >Edit</a
                        >
                    </div>
                </div>

                <!-- Masterlist Submenu -->
                <div class="mt-2">
                    <button
                        @click="toggleSubMenu('masterlist')"
                        class="flex items-center justify-between w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
                    >
                        <span class="flex items-center">
                            <svg
                                class="w-5 h-5 mr-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                ></path>
                            </svg>
                            Masterlist
                        </span>
                        <svg
                            :class="{
                                'transform rotate-180':
                                    openSubMenu === 'masterlist',
                            }"
                            class="w-4 h-4 transition-transform duration-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            ></path>
                        </svg>
                    </button>
                    <div
                        v-show="openSubMenu === 'masterlist'"
                        class="mt-2 py-2 bg-gray-50"
                    >
                        <a
                            href="#"
                            class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                            >Import</a
                        >
                        <a
                            href="#"
                            class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                            >Export</a
                        >
                    </div>
                </div>

                <!-- Documents Submenu -->
                <div class="mt-2">
                    <button
                        @click="toggleSubMenu('documents')"
                        class="flex items-center justify-between w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
                    >
                        <span class="flex items-center">
                            <svg
                                class="w-5 h-5 mr-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                ></path>
                            </svg>
                            Documents
                        </span>
                        <svg
                            :class="{
                                'transform rotate-180':
                                    openSubMenu === 'documents',
                            }"
                            class="w-4 h-4 transition-transform duration-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            ></path>
                        </svg>
                    </button>
                    <div
                        v-show="openSubMenu === 'documents'"
                        class="mt-2 py-2 bg-gray-50"
                    >
                        <a
                            href="#"
                            class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                            >Payroll Form</a
                        >
                        <a
                            href="#"
                            class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                            >Cash Report</a
                        >
                        <a
                            href="#"
                            class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                            >RCD</a
                        >
                        <a
                            href="#"
                            class="block px-6 py-2 text-gray-600 hover:bg-gray-100"
                            >CDR</a
                        >
                    </div>
                </div>
            </div>
        </nav>

        <!-- Footer -->
        <div class="mt-auto p-2 border-t">
            <p class="text-sm text-gray-600 text-center">DRMD@ACE 2024</p>
        </div>
    </aside>
</template>

<script setup lang="ts">
import { ref } from "vue";

const props = defineProps<{
    isOpen: boolean;
}>();

const emit = defineEmits(["open-modal"]);

const openSubMenu = ref(null);

const toggleSubMenu = (menu: string) => {
    if (openSubMenu.value === menu) {
        openSubMenu.value = null;
    } else {
        openSubMenu.value = menu;
    }
};
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
