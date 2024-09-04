<template>
    <div
        class="h-screen flex flex-col overflow-hidden bg-gray-100"
        :class="{ dark: isDarkMode }"
    >
        <!-- Top navigation bar -->
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
                    <span class="text-xl font-semibold text-gray-800"
                        >DRMD</span
                    >
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

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside
                v-if="isSidebarOpen"
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
                        Admin Dashboard
                    </a>
                    <div class="mt-4">
                        <h3
                            class="px-6 text-xs font-semibold text-gray-600 uppercase tracking-wide"
                        >
                            Main Menu
                        </h3>

                        <!-- Create Function -->
                        <button
                            @click="openCreateModal"
                            class="flex items-center w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
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
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                ></path>
                            </svg>
                            Create
                        </button>

                        <!-- Update Function -->
                        <button
                            @click="navigateToUpdate"
                            class="flex items-center w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
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
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                ></path>
                            </svg>
                            Update
                        </button>

                        <!-- Request Function -->
                        <button
                            @click="navigateToRequest"
                            class="flex items-center w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
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
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                ></path>
                            </svg>
                            Request
                        </button>
                    </div>
                </nav>

                <!-- Footer -->
                <div class="mt-auto p-2 border-t">
                    <p class="text-sm text-gray-600 text-center">
                        DRMD@ACE 2024
                    </p>
                </div>
            </aside>

            <!-- Main content -->
            <main
                class="flex-1 -mt-1.5 overflow-x-hidden overflow-y-auto bg-gray-100"
            >
                <div class="container mx-auto px-6 py-8">
                    <h3 class="text-gray-700 text-2xl font-medium">
                        Admin Dashboard
                    </h3>
                </div>
            </main>
        </div>

        <!-- Create Modal -->
        <div
            v-if="isCreateModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-xl text-center">
                <h2 class="text-xl font-bold mb-4">Create New User</h2>
                <p>Form fields for creating a new user would go here.</p>
                <button
                    @click="closeCreateModal"
                    class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import { useRouter } from "vue-router";

export default {
    setup() {
        const isDarkMode = ref(false);
        const isSidebarOpen = ref(true);
        const isCreateModalOpen = ref(false);
        const router = useRouter();

        const toggleDarkMode = () => {
            isDarkMode.value = !isDarkMode.value;
            if (isDarkMode.value) {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
        };

        const toggleSidebar = () => {
            isSidebarOpen.value = !isSidebarOpen.value;
        };

        const openCreateModal = () => {
            isCreateModalOpen.value = true;
        };

        const closeCreateModal = () => {
            isCreateModalOpen.value = false;
        };

        const navigateToUpdate = () => {
            router.push("/update");
        };

        const navigateToRequest = () => {
            router.push("/request");
        };

        return {
            isDarkMode,
            isSidebarOpen,
            isCreateModalOpen,
            toggleDarkMode,
            toggleSidebar,
            openCreateModal,
            closeCreateModal,
            navigateToUpdate,
            navigateToRequest,
        };
    },
};
</script>

<style scoped>
/* Add any additional styling here */
</style>
