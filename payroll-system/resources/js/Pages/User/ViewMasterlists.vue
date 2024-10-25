<template>
    <div class="h-screen flex flex-col overflow-hidden bg-gray-100">
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
            <main
                class="flex-1 px-14 -mt-1 overflow-x-hidden overflow-y-auto bg-gray-100"
            >
                <div class="container mx-auto px-6 py-8">
                    <div class="flex justify-between items-center -mb-5">
                        <div class="flex items-center">
                            <button
                                @click="goBack"
                                class="mr-4 text-gray-600 hover:text-gray-400 focus:outline-none"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                    />
                                </svg>
                            </button>
                            <h3 class="text-gray-900 text-2xl font-medium">
                                List of Masterlist
                            </h3>
                        </div>
                    </div>

                    <!-- Masterlists Table Component -->
                    <ViewMasterlistTable :masterlists="filteredMasterlists" />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import ViewMasterlistTable from "@/Layouts/ViewMasterlistTable.vue";
import axios from "axios";

// Sidebar functions
const isSidebarOpen = ref(true);
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const isDarkMode = ref(false);
const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    document.body.classList.toggle("dark", isDarkMode.value);
};

const openModal = () => {
    // Implement your modal opening logic here
};

const goBack = () => {
    if (window.history.length > 1) {
        window.history.back();
    }
};

const searchQuery = ref("");
const handleSearch = () => {
    // Implement your search logic here
    console.log("Searching for:", searchQuery.value);
};

const masterlists = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get("/api/masterlists");
        masterlists.value = response.data.masterlists;
    } catch (error) {
        console.error("Error fetching masterlists:", error);
    }
});

const filteredMasterlists = computed(() => {
    if (!searchQuery.value) return masterlists.value;
    const query = searchQuery.value.toLowerCase();
    return masterlists.value.filter(
        (masterlist) =>
            masterlist.masterlistName.toLowerCase().includes(query) ||
            masterlist.masterlistID.toString().includes(query)
    );
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

body {
    font-family: "Inter", sans-serif;
}

.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(-100%);
}
</style>
