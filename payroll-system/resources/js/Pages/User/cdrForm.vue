<template>
    <div class="h-screen flex flex-col overflow-hidden">
        <!-- Top navigation bar -->
        <NavBar @toggle-sidebar="toggleSidebar" @click="toggleDarkMode" />

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <transition name="slide">
                <Sidebar
                    v-if="isSidebarOpen"
                    :is-open="isSidebarOpen"
                    @open-modal="openModal"
                />
            </transition>

            <!-- Main Content -->
            <div class="p-6 px-14 flex flex-col w-full">
                <h1 class="text-2xl font-bold mb-4 text-center">
                    CASH DISBURSEMENT RECORD
                </h1>

                <!-- Dropdown Menus -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <!-- Accountable Officer Dropdown -->
                    <div>
                        <label
                            for="accountable-officer"
                            class="block font-bold mb-2"
                            >Accountable Officer</label
                        >
                        <select
                            id="accountable-officer"
                            class="w-full border border-gray-300 rounded p-2"
                        >
                            <option value="" disabled selected>
                                Select Accountable Officer
                            </option>
                            <option value="officer1">Officer 1</option>
                            <option value="officer2">Officer 2</option>
                            <option value="officer3">Officer 3</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>

                    <!-- Official Designation Dropdown -->
                    <div>
                        <label
                            for="official-designation"
                            class="block font-bold mb-2"
                            >Official Designation</label
                        >
                        <select
                            id="official-designation"
                            class="w-full border border-gray-300 rounded p-2"
                        >
                            <option value="" disabled selected>
                                Select Designation
                            </option>
                            <option value="designation1">Designation 1</option>
                            <option value="designation2">Designation 2</option>
                            <option value="designation3">Designation 3</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>

                    <!-- Station Dropdown -->
                    <div>
                        <label for="station" class="block font-bold mb-2"
                            >Station</label
                        >
                        <select
                            id="station"
                            class="w-full border border-gray-300 rounded p-2"
                        >
                            <option value="" disabled selected>
                                Select Station
                            </option>
                            <option value="station1">Station 1</option>
                            <option value="station2">Station 2</option>
                            <option value="station3">Station 3</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <table
                    class="min-w-full table-auto bg-white border border-gray-300 mt-4"
                >
                    <thead>
                        <tr class="bg-gray-200 text-center">
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">
                                ADA/Check/DV/Payroll/Reference No.
                            </th>
                            <th class="px-4 py-2">Payee</th>
                            <th class="px-4 py-2">UACS Object Code</th>
                            <th class="px-4 py-2">Nature of Payment</th>
                            <th class="px-4 py-2">
                                Cash Advance Received (Refunded)
                            </th>
                            <th class="px-4 py-2">Disbursements</th>
                            <th class="px-4 py-2">Cash Advance Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";

const isSidebarOpen = ref(true);
const isDarkMode = ref(false);

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    document.body.classList.toggle("dark", isDarkMode.value);
};

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const openModal = () => {
    // Implement your modal opening logic here
};

const data = ref({});
</script>

<style scoped>
/* Add your component styles here */
</style>
