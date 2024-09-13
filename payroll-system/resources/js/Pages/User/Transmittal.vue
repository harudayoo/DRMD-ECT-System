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
            <div class="p-6 flex flex-col w-full">
                <h1 class="text-2xl font-bold mb-4 text-center">TRANSMITTAL</h1>

                <!-- SDO Dropdown Menu -->
                <div class="mb-4">
                    <label for="sdo" class="block mb-2 font-bold">SDO</label>
                    <select
                        id="sdo"
                        class="w-full border border-gray-300 rounded p-2"
                    >
                        <option value="" disabled selected>Select SDO</option>
                        <option value="sdo1">SDO 1</option>
                        <option value="sdo2">SDO 2</option>
                        <option value="sdo3">SDO 3</option>
                        <!-- Add more options as needed -->
                    </select>
                </div>

                <!-- Table -->
                <table
                    class="min-w-full table-auto bg-white border border-gray-300 mt-4 table-fixed"
                >
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="px-28 py-2 w-1/3">MUNICIPALITY</th>
                            <th class="px-28 py-2 w-1/3">CLAIMED BENES</th>
                            <th class="px-28 py-2 w-1/3">CLAIMED AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2 text-center"></td>
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
import "jspdf-autotable";

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
// Add your script logic here
</script>

<style scoped>
/* Add your component styles here */
</style>
