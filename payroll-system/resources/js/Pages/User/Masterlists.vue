<template>
  <div class="h-screen flex flex-col overflow-hidden bg-gray-100 dark:bg-gray-900">
    <!-- Top navigation bar -->
    <NavBar @toggle-sidebar="toggleSidebar" @click="toggleDarkMode" />

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <transition name="slide">
        <Sidebar v-if="isSidebarOpen" :is-open="isSidebarOpen" @open-modal="openModal" />
      </transition>

      <!-- Main content -->
      <main
        class="flex-1 -mt-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900"
      >
        <div class="container mx-auto px-6 py-8">
          <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
              <button
                @click="goBack"
                class="mr-4 text-gray-600 dark:text-gray-300 hover:text-gray-400 focus:outline-none"
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
              <h3 class="text-gray-700 dark:text-gray-200 text-2xl font-medium">
                Ula Beneficiaries
              </h3>
            </div>
            <div class="relative">
              <input
                type="text"
                v-model="searchQuery"
                @input="handleSearch"
                placeholder="Search..."
                class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-full pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-72 h-9 transition-all duration-300 ease-in-out"
              />
              <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="18"
                  height="18"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="3"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="lucide lucide-search text-gray-400"
                >
                  <circle cx="11" cy="11" r="8" />
                  <path d="m21 21-4.3-4.3" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Beneficiaries Table Component -->
          <MasterlistTable :beneficiaries="beneficiaries" />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import MasterlistTable from "@/Layouts/MasterlistTable.vue";

// Sidebar functions
const isSidebarOpen = ref(true);

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const isDarkMode = ref(false); // Track dark mode state

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
  // Apply dark mode class to body or root element
  document.body.classList.toggle("dark", isDarkMode.value);
};

const openModal = () => {
  // Implement your modal opening logic here
};

const router = useRouter();

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

const props = defineProps({
  barangay: Object,
  beneficiaries: Array,
});

const beneficiaries = ref(props.beneficiaries);
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
