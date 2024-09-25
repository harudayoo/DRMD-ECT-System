<template>
  <div class="h-screen flex flex-col overflow-hidden bg-gray-100">
    <!-- Top navigation bar -->
    <NavBar @toggle-sidebar="toggleSidebar" @click="toggleDarkMode" />

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <transition name="slide">
        <Sidebar v-if="isSidebarOpen" :is-open="isSidebarOpen" @open-modal="openModal" />
      </transition>

      <!-- Main content -->
      <div class="p-6 flex px-14 flex-col w-full">
        <div class="flex justify-between items-center mb-4">
          <h1 class="text-2xl font-bold">Cash Assistance Payroll</h1>
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
</style>
