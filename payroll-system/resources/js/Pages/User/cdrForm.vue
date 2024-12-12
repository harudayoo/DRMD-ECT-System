<template>
    <div class="min-h-screen flex flex-col bg-gray-100">
        <!-- Top navigation bar -->
        <NavBar 
        :show="isSidebarVisible"
        @toggle-sidebar="toggleSidebar" 
        />

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <transition name="slide">
                <Sidebar v-if="isSidebarVisible" :is-open="isSidebarVisible" />
            </transition>

            <!-- Main content -->
            <main
                class="flex-1 px-14 -mt-1 overflow-x-hidden overflow-y-auto bg-gray-100"
            >
                <div class="container mx-auto px-6 py-8">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <h3 class="text-gray-900 text-2xl font-medium">
                                Cash Disbursement Record
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
        <newCDRmodal
            v-if="isNewCDRModalOpen"
            :initial-payrolls="initialPayrolls"
            :is-loading="isLoading"
            @close="closeNewCDRModal"
            @submit="handleCDRSubmit"
        />
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/vue3";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import cdrView from "@/Components/cdrView.vue";
import newCDRmodal from "@/Components/newCDRmodal.vue";

interface Payroll {
    payrollID: number;
    payrollNumber: string;
    payrollName: string;
    barangayID: number | null;
    subTotal: number;
    exportNum: number;
    created_at: string | null;
    updated_at: string | null;
}

const props = defineProps<{
    cdrs: Array<{
        cdrID: string | number;
        cdrName: string;
        payrollNumber: number;
    }>;
    rcds: Array<{
        id: string | number;
    }>;
    initialPayrolls: Payroll[];
}>();

const isSidebarVisible = ref(true);
const isNewCDRModalOpen = ref(false);
const isLoading = ref(false);

const toggleSidebar = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
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

const handleCDRSubmit = (cdrData: { cdrName: string; rcdID: string }) => {
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
</script>
