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
                class="flex-1 -mt-3 px-14 overflow-x-hidden overflow-y-auto bg-gray-100"
            >
                <div class="container mx-auto px-6 py-8">
                    <div class="flex justify-between items-center mb-4">
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
                            <h3 class="text-gray-700 text-2xl font-medium">
                                {{ pageTitle }}
                            </h3>
                        </div>
                        <div class="relative">
                            <input
                                type="text"
                                v-model="searchQuery"
                                @input="handleSearch"
                                placeholder="Search..."
                                class="bg-white text-gray-900 rounded-full pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-72 h-9 transition-all duration-300 ease-in-out"
                            />
                            <div
                                class="absolute left-3 top-1/2 transform -translate-y-1/2"
                            >
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
                    <MasterlistTable
                        v-if="beneficiaries.length > 0"
                        :beneficiaries="beneficiaries"
                        :barangay="barangay"
                    />
                    <p v-else class="text-center text-gray-500 mt-4">
                        No beneficiaries found for this barangay.
                    </p>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import MasterlistTable from "@/Layouts/MasterlistTable.vue";

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

const page = usePage();

const pageTitle = computed(() => {
    return (
        page.props.barangayName ||
        props.barangay?.barangayName ||
        "Beneficiaries"
    );
});

const goBack = () => {
    router.visit(
        route("barangays.index", { municipalityID: page.props.municipalityID })
    );
};

const searchQuery = ref("");

const handleSearch = () => {
    console.log("Searching for:", searchQuery.value);
};

const props = defineProps({
    barangay: {
        type: Object as () => { barangayName: string } | undefined,
        required: false,
    },
    beneficiaries: {
        type: Array,
        required: true,
    },
});

const beneficiaries = ref(props.beneficiaries || []);

onMounted(() => {
    if (!page.props.barangayName && !props.barangay?.barangayName) {
        console.warn(
            "No barangay name provided in page props or component props"
        );
    }
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
