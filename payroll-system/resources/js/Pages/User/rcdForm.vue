<template>
    <div class="min-h-screen flex flex-col bg-gray-100">
        <!-- Top navigation bar -->
        <NavBar @toggle-sidebar="toggleSidebar" @click="toggleDarkMode" />

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <transition name="slide">
                <Sidebar v-if="isSidebarOpen" :is-open="isSidebarOpen" />
            </transition>

            <!-- Main content -->
            <main
                class="flex-1 px-14 -mt-1 overflow-x-hidden overflow-y-auto bg-gray-100"
            >
                <div class="container mx-auto px-6 py-8">
                    <div class="flex justify-between items-center mb-2">
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
                                Report of Cash Disbursement
                            </h3>
                        </div>
                        <button
                            @click="openNewRCDModal"
                            class="text-center px-4 py-1.5 bg-blue-900 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-150 ease-in-out flex items-center space-x-2"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span>Generate New Report</span>
                        </button>
                    </div>

                    <rcdView :rcds="rcds" />
                </div>
            </main>
        </div>

        <!-- New RCD Modal -->
        <NewRCDModal
            v-if="isNewRCDModalOpen"
            :payrolls="payrolls"
            :is-loading="isLoading"
            @close="closeNewRCDModal"
            @submit="handleRCDSubmit"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/vue3";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import rcdView from "@/Components/rcdView.vue";
import NewRCDModal from "@/Components/NewRCDModal.vue";

const props = defineProps({
    rcds: Object,
    payrolls: {
        type: Array,
        default: () => [],
    },
});

const isSidebarOpen = ref(true);
const isDarkMode = ref(false);
const isNewRCDModalOpen = ref(false);
const isLoading = ref(false);

const goBack = () => {
    if (window.history.length > 1) {
        window.history.back();
    }
};

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    document.body.classList.toggle("dark", isDarkMode.value);
};

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const openNewRCDModal = () => {
    isNewRCDModalOpen.value = true;
};

const closeNewRCDModal = () => {
    isNewRCDModalOpen.value = false;
};

const form = useForm({
    rcdName: "",
    payrollID: "",
});

const handleRCDSubmit = (rcdData) => {
    form.rcdName = rcdData.rcdName;
    form.payrollID = rcdData.payrollID;

    isLoading.value = true;

    form.post(route("rcds.store"), {
        preserveScroll: true,
        onSuccess: () => {
            isLoading.value = false;
            closeNewRCDModal();
            Inertia.reload({ only: ["rcds"] });
        },
        onError: (errors) => {
            isLoading.value = false;
            console.error("Error submitting RCD:", errors);
            // Pass errors back to the modal
            if (isNewRCDModalOpen.value) {
            }
        },
    });
};

onMounted(() => {
    // Any initialization logic if needed
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

/* Ensure the content area takes up at least the full viewport height */
.min-h-screen {
    min-height: 100vh;
}

/* Allow the main content to scroll independently */
.overflow-auto {
    overflow-y: auto;
    max-height: calc(
        100vh - 64px
    ); /* Adjust this value based on your NavBar height */
}
</style>
