<template>
  <div
    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
    @click.self="closeModal"
  >
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-2xl">
      <h2 class="text-2xl font-bold mb-4 text-center">Generate New RCD</h2>
      <form @submit.prevent="submitRcd">
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2">
            <label for="rcdName" class="block mb-2">RCD Name</label>
            <input
              type="text"
              id="rcdName"
              v-model="form.rcdName"
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
              :disabled="isLoading"
            />
            <div v-if="form.errors.rcdName" class="mt-2 text-red-500">
              {{ form.errors.rcdName }}
            </div>
          </div>
          <div class="col-span-2">
            <label for="payrollID" class="block mb-2">Payroll</label>
            <select
              id="payrollID"
              v-model="form.payrollID"
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
              :disabled="isLoading"
            >
              <option value="" disabled>Select Payroll</option>
              <option
                v-for="payroll in payrolls"
                :key="payroll.payrollID"
                :value="payroll.payrollID"
              >
                {{ payroll.payrollNumber }} - {{ payroll.payrollName }}
              </option>
            </select>
            <div v-if="form.errors.payrollID" class="mt-2 text-red-500">
              {{ form.errors.payrollID }}
            </div>
          </div>
        </div>
        <div v-if="generalError" class="mt-2 text-red-500">
          {{ generalError }}
        </div>
        <div class="mt-6 flex justify-end space-x-4">
          <button
            type="button"
            @click="closeModal"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-full hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
            :disabled="isLoading"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-900 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center"
            :disabled="isLoading"
          >
            <svg
              v-if="isLoading"
              class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
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
            {{ isLoading ? "Creating..." : "Create RCD" }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
  payrolls: {
    type: Array,
    required: true,
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["close", "submit"]);

const form = useForm({
  rcdName: "",
  payrollID: "",
});

const generalError = ref("");

const submitRcd = () => {
  generalError.value = "";
  if (form.rcdName && form.payrollID) {
    emit("submit", {
      rcdName: form.rcdName,
      payrollID: form.payrollID,
    });
  } else {
    if (!form.rcdName) form.errors.rcdName = "The RCD name field is required.";
    if (!form.payrollID) form.errors.payrollID = "The payroll ID field is required.";
  }
};

const closeModal = () => {
  emit("close");
};
</script>
