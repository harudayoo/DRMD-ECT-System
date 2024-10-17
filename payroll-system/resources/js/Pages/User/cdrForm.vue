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
                                Cash Disbursement Report
                            </h3>
                        </div>
                        <button
                            @click="openNewCDRModal"
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

                    <cdrView :cdrs="cdrs" />
                </div>
            </main>
        </div>

        <!-- New CDR Modal -->
        <NewCDRModal
            v-if="isNewCDRModalOpen"
            :rcds="rcds"
            :is-loading="isLoading"
            @close="closeNewCDRModal"
            @submit="handleCDRSubmit"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/vue3";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import cdrView from "@/Components/cdrView.vue";
import NewCDRModal from "@/Components/NewCDRModal.vue";

const props = defineProps({
    cdrs: Object,
    rcds: {
        type: Array,
        default: () => [],
    },
});

const isSidebarOpen = ref(true);
const isDarkMode = ref(false);
const isNewCDRModalOpen = ref(false);
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

const openNewCDRModal = () => {
    isNewCDRModalOpen.value = true;
};

const closeNewCDRModal = () => {
    isNewCDRModalOpen.value = false;
};

const form = useForm({
    cdrName: "",
    rcdID: "",
});

const handleCDRSubmit = (cdrData) => {
    form.cdrName = cdrData.cdrName;
    form.rcdID = cdrData.rcdID;

    isLoading.value = true;

    form.post(route("cdrs.store"), {
        preserveScroll: true,
        onSuccess: () => {
            isLoading.value = false;
            closeNewCDRModal();
            Inertia.reload({ only: ["cdrs"] });
        },
        onError: (errors) => {
            isLoading.value = false;
            console.error("Error submitting CDR:", errors);
        },
    });
};

onMounted(() => {
    // Any initialization logic if needed
});
</script>
