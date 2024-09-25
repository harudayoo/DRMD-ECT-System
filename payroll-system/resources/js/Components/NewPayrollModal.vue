<template>
  <div
    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
  >
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-2xl">
      <h2 class="text-2xl font-bold mb-4 text-center">New Payroll</h2>
      <form @submit.prevent="submitPayroll">
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2">
            <label for="payrollName" class="block mb-2">Payroll Name</label>
            <input
              type="text"
              id="payrollName"
              v-model="payroll.payrollName"
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
              :disabled="isLoading"
            />
          </div>
          <div>
            <label for="province" class="block mb-2">Province</label>
            <select
              id="province"
              v-model="payroll.provinceID"
              @change="handleProvinceChange"
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
              :disabled="isLoading"
            >
              <option value="" disabled>Select Province</option>
              <option
                v-for="province in provinces"
                :key="province.provinceID"
                :value="province.provinceID"
              >
                {{ province.provinceName }}
              </option>
            </select>
          </div>
          <div>
            <label for="municipality" class="block mb-2">Municipality</label>
            <select
              id="municipality"
              v-model="payroll.municipalityID"
              @change="handleMunicipalityChange"
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
              :disabled="!payroll.provinceID || isLoading"
            >
              <option value="" disabled>Select Municipality</option>
              <option
                v-for="municipality in municipalities"
                :key="municipality.municipalityID"
                :value="municipality.municipalityID"
              >
                {{ municipality.municipalityName }}
              </option>
            </select>
          </div>
          <div class="col-span-2">
            <label for="barangay" class="block mb-2">Barangay</label>
            <select
              id="barangay"
              v-model="payroll.barangayID"
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
              :disabled="!payroll.municipalityID || isLoading"
            >
              <option value="" disabled>Select Barangay</option>
              <option
                v-for="barangay in barangays"
                :key="barangay.barangayID"
                :value="barangay.barangayID"
              >
                {{ barangay.barangayName }}
              </option>
            </select>
          </div>
        </div>
        <div v-if="error" class="mt-4 text-red-500">
          {{ error }}
        </div>
        <div class="mt-6 flex justify-end space-x-4">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
            :disabled="isLoading"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center"
            :disabled="isLoading"
          >
            <span v-if="isLoading" class="mr-2">
              <svg
                class="animate-spin h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
            </span>
            {{ isLoading ? "Creating..." : "Create Payroll" }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import axios from "axios";

const props = defineProps({
  provinces: Array,
});

const emit = defineEmits(["close", "fetch-municipalities", "fetch-barangays", "submit"]);

const payroll = ref({
  payrollName: "",
  provinceID: "",
  municipalityID: "",
  barangayID: "",
});

const municipalities = ref([]);
const barangays = ref([]);
const isLoading = ref(false);
const error = ref(null);

const handleProvinceChange = async () => {
  payroll.value.municipalityID = "";
  payroll.value.barangayID = "";
  municipalities.value = [];
  barangays.value = [];

  if (payroll.value.provinceID) {
    try {
      isLoading.value = true;
      error.value = null;
      const response = await axios.get(route("api.municipalities.index"), {
        params: { provinceID: payroll.value.provinceID },
      });
      municipalities.value = response.data.municipalities;
    } catch (err) {
      console.error("Error fetching municipalities:", err);
      error.value = "Failed to fetch municipalities. Please try again.";
    } finally {
      isLoading.value = false;
    }
  }
};

const handleMunicipalityChange = async () => {
  payroll.value.barangayID = "";
  barangays.value = [];

  if (payroll.value.municipalityID) {
    try {
      isLoading.value = true;
      error.value = null;
      const response = await axios.get(route("api.barangays.index"), {
        params: { municipalityID: payroll.value.municipalityID },
      });
      barangays.value = response.data.barangays;
    } catch (err) {
      console.error("Error fetching barangays:", err);
      error.value = "Failed to fetch barangays. Please try again.";
    } finally {
      isLoading.value = false;
    }
  }
};

const submitPayroll = async () => {
  if (!payroll.value.payrollName || !payroll.value.barangayID) {
    error.value = "Payroll name and barangay are required";
    return;
  }

  try {
    isLoading.value = true;
    error.value = null;
    await emit("submit", payroll.value);
    // If the emit doesn't throw an error, we assume it was successful
    emit("close");
  } catch (err) {
    console.error("Error submitting payroll:", err);
    error.value = "Failed to create payroll. Please try again.";
  } finally {
    isLoading.value = false;
  }
};
</script>
