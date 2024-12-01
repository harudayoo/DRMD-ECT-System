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
      <main class="flex-1 px-14 -mt-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="container mx-auto px-6 py-8">
          <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
              <h3 class="text-gray-900 text-2xl ml-2 font-medium">
                List of Beneficiaries
              </h3>
            </div>
            <div class="relative">
              <input
                type="text"
                v-model="searchQuery"
                @input="handleSearch"
                placeholder="Search Name or Beneficiary Number"
                class="bg-white text-gray-700 rounded-full pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-80 h-9 transition-all duration-300 ease-in-out"
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
          <BeneficiaryTable
            :beneficiaries="filteredBeneficiaries"
            @edit-beneficiary="showEditModal"
          />
        </div>
      </main>
    </div>

    <!-- Edit Beneficiary Modal -->
    <EditBeneficiaryModal
      v-if="showModal"
      :beneficiary="editedBeneficiary"
      @close="closeModal"
      @save="updateBeneficiary"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import axios from "axios"; // Import axios
import NavBar from "@/Layouts/NavBar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import BeneficiaryTable from "@/Layouts/BeneficiaryTable.vue";
import EditBeneficiaryModal from "@/Components/EditBeneficiaryModal.vue";

// Define an interface for the Beneficiary type
interface Beneficiary {
  beneficiaryID: number;
  lastName: string;
  firstName: string;
  middleName?: string;
  beneficiaryNumber: string;
  sex: number;
  dateOfBirth?: string | Date;
  // Add other properties as needed
}

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

const props = defineProps<{
  beneficiaries: Beneficiary[];
}>();

const filteredBeneficiaries = computed(() => {
  if (!searchQuery.value) return props.beneficiaries;
  const query = searchQuery.value.toLowerCase();
  return props.beneficiaries.filter(
    (beneficiary) =>
      beneficiary.lastName.toLowerCase().includes(query) ||
      beneficiary.firstName.toLowerCase().includes(query) ||
      beneficiary.middleName?.toString().includes(query) ||
      beneficiary.beneficiaryNumber.toString().includes(query)
  );
});

const showModal = ref(false);
const editedBeneficiary = ref<Beneficiary>({} as Beneficiary);

const showEditModal = (beneficiary: Beneficiary) => {
  editedBeneficiary.value = { ...beneficiary };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const updateBeneficiary = async (updatedBeneficiary: Beneficiary) => {
  try {
    // Convert date to YYYY-MM-DD format
    if (updatedBeneficiary.dateOfBirth) {
      updatedBeneficiary.dateOfBirth = new Date(updatedBeneficiary.dateOfBirth)
        .toISOString()
        .split("T")[0];
    }

    const response = await axios.put(
      `/api/beneficiaries/${updatedBeneficiary.beneficiaryID}`,
      updatedBeneficiary
    );

    if (response.status === 200) {
      const updatedData = response.data;
      const index = props.beneficiaries.findIndex(
        (b) => b.beneficiaryID === updatedData.beneficiaryID
      );
      if (index !== -1) {
        props.beneficiaries[index] = updatedData;
      }
      closeModal();
      // Optionally, you can show a success message here
    } else {
      console.error("Failed to update beneficiary");
      // Optionally, you can show an error message here
    }
  } catch (error) {
    console.error("Error updating beneficiary:", error);
    // Optionally, you can show an error message here
  }
};
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
