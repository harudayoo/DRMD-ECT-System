<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <!-- Top navigation bar -->
    <NavBar @toggle-sidebar="toggleSidebar" @click="toggleDarkMode" />

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <transition name="slide">
        <Sidebar
          v-if="isSidebarOpen"
          :is-open="isSidebarOpen"
          @open-modal="handleOpenModal"
        />
      </transition>

      <!-- Main content -->
      <main class="flex-1 px-14 -mt-1 overflow-x-hidden overflow-y-auto bg-gray-100">
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
              <h3 class="text-gray-900 text-2xl font-medium">Cash Assistance Payroll</h3>
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

const isSidebarOpen = ref(true);
const isDarkMode = ref(false);
const isNewPayrollModalOpen = ref(false);
const provinces = ref<Province[]>([]);
const municipalities = ref<Municipality[]>([]);
const masterlists = ref<Masterlist[]>([]);

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

const handleOpenModal = () => {
  // Implement any specific modal opening logic if needed
  console.log("Modal open requested");
};

const openNewPayrollModal = () => {
  isNewPayrollModalOpen.value = true;
  fetchProvinces();
};

const closeNewPayrollModal = () => {
  isNewPayrollModalOpen.value = false;
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
      // Optionally, refresh the payroll list
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
