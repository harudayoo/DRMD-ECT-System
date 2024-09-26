<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <!-- Top navigation bar -->
    <NavBar @toggle-sidebar="toggleSidebar" @click="toggleDarkMode" />

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <transition name="slide">
        <Sidebar v-if="isSidebarOpen" :is-open="isSidebarOpen" @open-modal="openModal" />
      </transition>

      <!-- Main content -->
      <div class="flex-1 overflow-auto">
        <div class="p-6 px-14">
          <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">Cash Assistance Payroll</h1>
            <button
              @click="openNewPayrollModal"
              class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              New
            </button>
          </div>

          <PayrollView :payrolls="payrolls" />
        </div>
      </div>
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
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import PayrollView from "@/Components/PayrollView.vue";
import NewPayrollModal from "@/Components/NewPayrollModal.vue";
import { useForm } from "@inertiajs/vue3";

defineProps({
  payrolls: Object,
});

const isSidebarOpen = ref(true);
const isDarkMode = ref(false);
const isNewPayrollModalOpen = ref(false);
const provinces = ref([]);
const municipalities = ref([]);
const masterlists = ref([]);

//Vue Component consts

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
  document.body.classList.toggle("dark", isDarkMode.value);
};

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const openNewPayrollModal = () => {
  isNewPayrollModalOpen.value = true;
  fetchProvinces();
};

const closeNewPayrollModal = () => {
  isNewPayrollModalOpen.value = false;
};

//Modal Fetching Consts

const fetchProvinces = async () => {
  try {
    const response = await axios.get(route("api.provinces.index"));
    provinces.value = response.data.provinces;
  } catch (error) {
    console.error("Error fetching provinces:", error);
  }
};

const fetchMunicipalities = async (provinceId) => {
  try {
    const response = await axios.get(route("api.municipalities.index"), {
      params: { provinceID: provinceId },
    });
    municipalities.value = response.data.municipalities;
  } catch (error) {
    console.error("Error fetching municipalities:", error);
  }
};

const fetchMasterlists = async (municipalityId) => {
  try {
    const response = await axios.get(route("api.masterlists.index"), {
      params: { municipalityID: municipalityId },
    });
    masterlists.value = response.data.masterlists;
  } catch (error) {
    console.error("Error fetching masterlists:", error);
  }
};

//Payroll Submission

const form = useForm({
  payrollName: "",
  barangayID: "",
});

const handlePayrollSubmit = (payrollData) => {
  console.log("Handling payroll submit:", payrollData);
  form.payrollName = payrollData.payrollName;
  form.barangayID = payrollData.barangayID;

  form.post(route("payroll.store"), {
    preserveScroll: true,
    onSuccess: () => {
      closeNewPayrollModal();
      // Optionally, refresh the payroll list
      Inertia.reload({ only: ["payrolls"] });
    },
    onError: (errors) => {
      console.error("Error submitting payroll:", errors);
    },
  });
};

//Onmounteds

onMounted(() => {
  fetchProvinces();
});
</script>

<style scoped>
/* Add your component styles here */
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
  max-height: calc(100vh - 64px); /* Adjust this value based on your NavBar height */
}
</style>
