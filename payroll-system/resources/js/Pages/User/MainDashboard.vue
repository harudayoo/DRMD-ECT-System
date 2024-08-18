<template>
    <div class="h-screen flex flex-col overflow-hidden">
        <!-- Top bar -->
        <nav
            class="bg-sky-900 shadow-2xl flex items-center justify-between px-4 py-1"
        >
            <!-- Menu Toggle Button -->
            <button
                @click="toggleMenu"
                class="text-white focus:outline-none hover:text-red-700 transition-colors duration-200"
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
                        viewBox="0 24 24 24"
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
            class="fixed top-0 left-0 h-full w-80 bg-gray-800 bg-opacity-90 text-white transform transition-transform duration-300 ease-in-out z-50 overflow-y-auto"
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
                                >Edit</a
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
                            <a
                                href="#"
                                class="block text-xl hover:text-gray-400"
                                >Archive</a
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

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header>
                <div class="ml-10 mx-auto py-2 px-4">
                    <h1 class="text-2xl font-black text-black">
                        ECT Payroll System Dashboard
                    </h1>
                </div>
            </header>

            <!-- Chart and Statistics -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Main Bar Chart -->
                <div class="flex-1 flex justify-center items-center -mt-24">
                    <div style="width: 80%; height: 65%">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>

                <!-- Statistics and Table -->
                <div class="flex h-2/6 mx-4 mb-4 -mt-4">
                    <!-- Statistics -->
                    <div class="text-black text-xl -mt-4 ml-20">
                        <div class="font-semibold">
                            Total: <span class="font-black">165</span>
                        </div>
                        <div class="font-semibold">
                            Pending: <span class="font-black">120</span>
                        </div>
                        <div class="font-semibold">
                            Approved: <span class="font-black">40</span>
                        </div>
                        <div class="font-semibold">
                            Disapproved: <span class="font-black">5</span>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto rounded-xl -mt-14 ml-14 w-3/4">
                        <table class="min-w-full bg-gray-100">
                            <thead>
                                <tr>
                                    <th
                                        class="py-2 px-4 bg-stone-300 text-left"
                                    >
                                        Province Name
                                    </th>
                                    <th
                                        class="py-2 px-4 bg-stone-300 text-left"
                                    >
                                        Total Beneficiaries
                                    </th>
                                    <th
                                        class="py-2 px-4 bg-stone-300 text-left"
                                    >
                                        Total Amount Released
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="province in provinces"
                                    :key="province.provinceID"
                                    @click="
                                        goToMunicipalities(province.provinceID)
                                    "
                                    class="border-t border-b border-gray-300 cursor-pointer hover:bg-gray-200"
                                >
                                    <td class="py-2 px-4">
                                        {{ province.provinceName }}
                                    </td>
                                    <td class="py-2 px-4">
                                        {{ province.totalBeneficiaries }}
                                    </td>
                                    <td class="py-2 px-4">
                                        ₱{{
                                            parseFloat(
                                                province.totalAmountReleased
                                            ).toFixed(2)
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
import axios from "axios";

export default {
    components: {
        Link,
    },
    props: {
        provinces: Array,
    },
    setup(props) {
        const tableData = ref([
            // Add more data here as needed
        ]);

        const goToMunicipalities = (provinceID) => {
            window.location.href = `/municipalities/${provinceID}`;
        };

        const isMenuOpen = ref(false);
        const isUserMenuOpen = ref(false);
        const openSubMenu = ref(null);

        const toggleMenu = () => {
            isMenuOpen.value = !isMenuOpen.value;
        };

        const toggleUserMenu = () => {
            isUserMenuOpen.value = !isUserMenuOpen.value;
        };

        const toggleSubMenu = (menuName) => {
            openSubMenu.value =
                openSubMenu.value === menuName ? null : menuName;
        };

        const logout = async () => {
            try {
                // Send a POST request to the logout route
                await axios.post(route("logout"));

                // If successful, redirect to the login page
                window.location.href = route("login");
            } catch (error) {
                console.error("Logout failed:", error);
                // Handle the error (e.g., show an error message to the user)
            }
        };

        onMounted(() => {
            const ctx = document.getElementById("myChart");
            if (ctx) {
                new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: [
                            "January",
                            "February",
                            "March",
                            "April",
                            "May",
                            "June",
                        ],
                        datasets: [
                            {
                                label: "Monthly Data",
                                data: [10, 20, 30, 40, 50, 60],
                                backgroundColor: "#f87979",
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    },
                });
            }
        });

        return {
            tableData,
            isMenuOpen,
            isUserMenuOpen,
            openSubMenu,
            toggleMenu,
            toggleUserMenu,
            toggleSubMenu,
            logout,
            provinces: props.provinces,
            goToMunicipalities,
        };
    },
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");

.font-inter {
    font-family: "Inter", sans-serif;
}
</style>
