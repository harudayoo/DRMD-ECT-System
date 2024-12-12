<template>
    <div class="min-h-screen flex flex-col bg-gray-100">
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
                    @open-modal="handleOpenModal"
                />
            </transition>

            <!-- Main content -->
            <main
                class="flex-1 px-14 -mt-1 overflow-x-hidden overflow-y-auto bg-gray-100"
            >
                <div class="container mx-auto px-6 py-8">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <h3 class="text-gray-900 text-2xl font-medium">
                                Cash Assistance Payroll
                            </h3>
                        </div>
                        <button
                            @click="openNewPayrollModal"
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
                            <span>New Payroll</span>
                        </button>
                    </div>

                    <PayrollView :payrolls="payrolls" />
                </div>
            </main>
        </div>

        <!-- New Payroll Modal -->
        <NewPayrollModal
            v-if="isNewPayrollModalOpen"
            @close="closeNewPayrollModal"
            :provinces="provinces"
            @submit="handlePayrollSubmit"
        />

        <!-- Success Modal -->
        <div
            v-if="isSuccessModalOpen"
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <!-- Backdrop -->
            <div
                class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
            ></div>

            <!-- Modal -->
            <div
                class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
            >
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-2 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6"
                >
                    <!-- Success Icon -->
                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-300"
                    >
                        <svg
                            class="h-8 w-8 text-green-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="4"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.5 12.75l6 6 9-13.5"
                            />
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="mt-3 text-center">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Payroll Created Successfully!
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Your new payroll "{{ form.payrollName }}" has
                                been created and is ready for processing.
                            </p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-5 sm:mt-6 text-center">
                        <button
                            type="button"
                            class="inline-flex w-2/4 justify-center rounded-md bg-blue-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300"
                            @click="closeSuccessModal"
                        >
                            Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import { router } from "@inertiajs/vue3";
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import PayrollView from "@/Components/PayrollView.vue";
import NewPayrollModal from "@/Components/NewPayrollModal.vue";
import { useForm } from "@inertiajs/vue3";

interface Province {
    id: number;
    name: string;
}

interface Municipality {
    id: number;
    name: string;
}

interface Masterlist {
    id: number;
    name: string;
}

interface PayrollData {
    payrollName: string;
    barangayID: string;
}

defineProps<{
    payrolls: Object;
}>();

const isSidebarVisible = ref(true);
const isNewPayrollModalOpen = ref(false);
const isSuccessModalOpen = ref(false);
const provinces = ref<Province[]>([]);
const municipalities = ref<Municipality[]>([]);
const masterlists = ref<Masterlist[]>([]);

const toggleSidebar = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};

const handleOpenModal = () => {
    console.log("Modal open requested");
};

const openNewPayrollModal = () => {
    isNewPayrollModalOpen.value = true;
    fetchProvinces();
};

const closeNewPayrollModal = () => {
    isNewPayrollModalOpen.value = false;
};

const closeSuccessModal = () => {
    isSuccessModalOpen.value = false;
};

const fetchProvinces = async () => {
    try {
        const response = await axios.get(route("api.provinces.index"));
        provinces.value = response.data.provinces;
    } catch (error) {
        console.error("Error fetching provinces:", error);
    }
};

const fetchMunicipalities = async (provinceId: number) => {
    try {
        const response = await axios.get(route("api.municipalities.index"), {
            params: { provinceID: provinceId },
        });
        municipalities.value = response.data.municipalities;
    } catch (error) {
        console.error("Error fetching municipalities:", error);
    }
};

const fetchMasterlists = async (municipalityId: number) => {
    try {
        const response = await axios.get(route("api.masterlists.index"), {
            params: { municipalityID: municipalityId },
        });
        masterlists.value = response.data.masterlists;
    } catch (error) {
        console.error("Error fetching masterlists:", error);
    }
};

const form = useForm({
    payrollName: "",
    barangayID: "",
});

const handlePayrollSubmit = (payrollData: PayrollData) => {
    console.log("Handling payroll submit:", payrollData);
    form.payrollName = payrollData.payrollName;
    form.barangayID = payrollData.barangayID;

    form.post(route("payroll.store"), {
        preserveScroll: true,
        onSuccess: () => {
            closeNewPayrollModal();
            isSuccessModalOpen.value = true; // Show success modal
            router.reload({ only: ["payrolls"] });
        },
        onError: (errors) => {
            console.error("Error submitting payroll:", errors);
        },
    });
};

onMounted(() => {
    fetchProvinces();
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

.min-h-screen {
    min-height: 100vh;
}

.overflow-auto {
    overflow-y: auto;
    max-height: calc(100vh - 64px);
}
</style>
