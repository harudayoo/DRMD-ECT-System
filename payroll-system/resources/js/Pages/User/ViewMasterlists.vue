<template>
    <div class="h-screen flex flex-col overflow-hidden bg-gray-100">
        <!-- Top navigation bar -->
        <NavBar 
        :show="isSidebarVisible"
        @toggle-sidebar="toggleSidebar" />
        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <transition name="slide">
                <Sidebar
                    v-if="isSidebarVisible"
                    :is-open="isSidebarVisible"
                    @open-modal="openModal"
                />
            </transition>
            <!-- Main content -->
            <main
                class="flex-1 px-14 -mt-2 overflow-x-hidden overflow-y-auto bg-gray-100"
            >
                <div class="container mx-auto px-6 py-8">
                    <!-- Modified header section -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center">
                            <h3
                                v-if="!showBeneficiaries"
                                class="text-gray-900 ml-4 text-2xl font-medium"
                            >
                                List of Masterlists
                            </h3>
                        </div>
                    </div>
                    <!-- Masterlists Table Component -->
                    <ViewMasterlistTable
                        :masterlists="filteredMasterlists"
                        :showbeneficiaries="showBeneficiaries"
                    />
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

// Define the Masterlist interface
interface Masterlist {
    masterlistName: string;
    masterlistID: number;
}

// Add showBeneficiaries state
const showBeneficiaries = ref(false);

// Sidebar functions
const isSidebarVisible = ref(true);
const toggleSidebar = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};

const openModal = () => {
    // Implement your modal opening logic here
};

const searchQuery = ref("");
const handleSearch = () => {
    // Implement your search logic here
    console.log("Searching for:", searchQuery.value);
};

const masterlists = ref<Masterlist[]>([]);

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
