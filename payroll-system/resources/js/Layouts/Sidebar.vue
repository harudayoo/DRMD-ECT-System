<template>
    <div class="h-screen flex flex-col overflow-hidden">
        <!-- Top bar -->
        <nav
            class="bg-sky-900 shadow-2xl flex items-center justify-between px-4 py-.5"
        >
            <!-- Menu Toggle Button -->
            <button
                @click="toggleMenu"
                class="text-white focus:outline-none hover:text-red-700 transition-colors duration-200 menu-toggle"
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
                        stroke-width="2.5"
                        d="M4 6h16M4 12h16M4 18h16"
                    ></path>
                </svg>
            </button>

            <!-- User Menu Toggle Button -->
            <div class="relative">
                <button
                    @click="toggleUserMenu"
                    class="text-white mt-1.5 focus:outline-none hover:text-red-700 transition-colors duration-200"
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
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                        ></path>
                    </svg>
                </button>
                <div
                    v-if="isUserMenuOpen"
                    class="absolute right-0 w-48 bg-white rounded-md shadow-lg py-1 z-10"
                >
                    <a
                        href="#"
                        @click="logout"
                        class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-300"
                    >
                        Log out
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <div
            class="fixed top-0 left-0 h-full w-80 bg-gray-800 bg-opacity-90 text-white transform transition-transform duration-300 ease-in-out z-50 overflow-y-auto sidebar"
            :class="{
                'translate-x-0': isMenuOpen,
                '-translate-x-full': !isMenuOpen,
            }"
        >
            <div class="p-4 relative">
                <!-- Logo and Title -->
                <span class="text-2xl font-semibold ml-14">
                    DSWD - DRMD
                    <img
                        src="/icons/main-logo.png"
                        alt="form-bg"
                        class="w-12 -mt-10"
                    />
                </span>
                <!-- Close Menu Button -->
                <button
                    @click="toggleMenu"
                    class="absolute top-2.5 right-4 text-white focus:outline-none hover:text-red-700 transition-colors duration-200"
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
                            stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </button>

                <!-- Navigation Links -->
                <ul class="mt-4 ml-4 font-thin">
                    <!-- Beneficiaries Submenu -->
                    <li class="mb-4 text-xl">
                        <div
                            @click="toggleSubMenu('beneficiaries')"
                            class="flex justify-between items-center cursor-pointer"
                        >
                            <a href="#" class="hover:text-gray-500"
                                >Beneficiaries</a
                            >
                            <svg
                                :class="{
                                    'rotate-90':
                                        openSubMenu === 'beneficiaries',
                                }"
                                class="w-5 h-5 text-white transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="3"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                        <div
                            v-show="openSubMenu === 'beneficiaries'"
                            class="mt-2 ml-4 rounded-md p-2"
                        >
                            <a
                                href="#"
                                @click="openModal"
                                class="block text-xl hover:text-gray-400"
                                >Add</a
                            >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Edit</a
                            >
                        </div>
                    </li>

                    <!-- Masterlist Submenu -->
                    <li class="mb-4 text-xl">
                        <div
                            @click="toggleSubMenu('masterlist')"
                            class="flex justify-between items-center cursor-pointer"
                        >
                            <a href="#" class="hover:text-gray-500"
                                >Masterlist</a
                            >
                            <svg
                                :class="{
                                    'rotate-90': openSubMenu === 'masterlist',
                                }"
                                class="w-5 h-5 text-white transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="3"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                        <div
                            v-show="openSubMenu === 'masterlist'"
                            class="mt-2 ml-4 rounded-md p-1"
                        >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Import</a
                            >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Export</a
                            >
                        </div>
                    </li>

                    <!-- Documents Submenu -->
                    <li class="mb-4 text-xl">
                        <div
                            @click="toggleSubMenu('documents')"
                            class="flex justify-between items-center cursor-pointer"
                        >
                            <a href="#" class="hover:text-gray-500"
                                >Documents</a
                            >
                            <svg
                                :class="{
                                    'rotate-90': openSubMenu === 'documents',
                                }"
                                class="w-5 h-5 text-white transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="3"
                                    d="M9 5l7 7-7 7"
                                ></path>
                            </svg>
                        </div>
                        <div
                            v-show="openSubMenu === 'documents'"
                            class="mt-2 ml-4 rounded-md p-1"
                        >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Payroll Form</a
                            >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Cash Report</a
                            >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >RCD</a
                            >
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >CDR</a
                            >
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Toggle Menu Button -->
            <button
                @click="toggleMenu"
                class="fixed top-4 left-4 z-40 text-gray-600 focus:outline-none hover:text-gray-900"
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

            <!-- Slot for page content -->
            <slot></slot>
        </div>

        <!-- Add Beneficiary Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
        >
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-2xl">
                <h2 class="text-2xl font-bold mb-4 text-center">
                    Add Beneficiary
                </h2>
                <form @submit.prevent="addBeneficiary">
                    <!-- Form fields here -->
                </form>
            </div>
        </div>

        <!-- Success Notification Modal -->
        <div
            v-if="isSuccessModalOpen"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
        >
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
                <div class="text-center">
                    <svg
                        class="mx-auto mb-4 w-14 h-14 text-green-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        ></path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500">
                        Beneficiary successfully added!
                    </h3>
                    <button
                        @click="closeSuccessModal"
                        type="button"
                        class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2"
                    >
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";

export default {
    name: "SidebarLayout",
    setup() {
        const isMenuOpen = ref(false);
        const isModalOpen = ref(false);
        const isSuccessModalOpen = ref(false);
        const openSubMenu = ref(null);

        const toggleMenu = () => {
            isMenuOpen.value = !isMenuOpen.value;
        };

        const toggleSubMenu = (menu) => {
            if (openSubMenu.value === menu) {
                openSubMenu.value = null;
            } else {
                openSubMenu.value = menu;
            }
        };

        const openModal = () => {
            isModalOpen.value = true;
        };

        const closeModal = () => {
            isModalOpen.value = false;
        };

        const openSuccessModal = () => {
            isSuccessModalOpen.value = true;
        };

        const closeSuccessModal = () => {
            isSuccessModalOpen.value = false;
        };

        const addBeneficiary = async () => {
            try {
                const response = await axios.post(
                    route("beneficiaries.store"),
                    beneficiary
                );
                console.log("Beneficiary added:", response.data);
                closeModal();
                openSuccessModal();
            } catch (error) {
                console.error("Error adding beneficiary:", error);
            }
        };

        const handleOutsideClick = (event) => {
            const sidebar = document.querySelector(".sidebar");
            const menuToggle = document.querySelector(".menu-toggle");

            if (
                isMenuOpen.value &&
                sidebar &&
                !sidebar.contains(event.target) &&
                !menuToggle.contains(event.target)
            ) {
                isMenuOpen.value = false;
            }
        };

        onMounted(() => {
            document.addEventListener("click", handleOutsideClick);
        });

        onUnmounted(() => {
            document.removeEventListener("click", handleOutsideClick);
        });

        return {
            isMenuOpen,
            isModalOpen,
            isSuccessModalOpen,
            openSubMenu,
            toggleMenu,
            toggleSubMenu,
            openModal,
            closeModal,
            openSuccessModal,
            closeSuccessModal,
            addBeneficiary,
            handleOutsideClick,
        };
    },
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
