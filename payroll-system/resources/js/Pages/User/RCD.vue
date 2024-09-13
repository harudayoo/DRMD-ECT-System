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

            <!-- Main content -->
            <div class="p-6 px-14 flex flex-col w-full">
                <h1 class="text-2xl font-bold mb-4 text-center">
                    REPORT OF CASH DISBURSEMENTS
                </h1>

                <!-- Table -->
                <table
                    class="min-w-full table-auto bg-white border border-gray-300 mt-4"
                >
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">DV/Payroll No.</th>
                            <th class="px-4 py-2">ORS/BURS No.</th>
                            <th class="px-4 py-2">
                                Responsibility Center Code
                            </th>
                            <th class="px-4 py-2">Payee</th>
                            <th class="px-4 py-2">UACS Object Code</th>
                            <th class="px-4 py-2">Nature of Payment</th>
                            <th class="px-4 py-2">Amount</th>
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
