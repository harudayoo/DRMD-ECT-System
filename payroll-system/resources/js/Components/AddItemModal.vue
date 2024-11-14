<template>
  <div
    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full"
    @click="$emit('close')"
  >
    <div
      class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
      @click.stop
    >
      <div class="mt-3 text-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Add New {{ getModalTitle }}
        </h3>

        <!-- Error Alert -->
        <div
          v-if="error"
          class="mt-2 p-2 bg-red-100 border border-red-400 text-red-700 rounded"
        >
          {{ error }}
        </div>

        <!-- Success Alert -->
        <div
          v-if="success"
          class="mt-2 p-2 bg-green-100 border border-green-400 text-green-700 rounded"
        >
          {{ success }}
        </div>

        <form @submit.prevent="addItem" class="mt-2 px-7 py-3 space-y-4">
          <template v-if="props.modalType === 'orsBurs'">
            <div class="space-y-2">
              <input
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                v-model.trim="formData.orsBurs"
                placeholder="Enter ORS/BURS Number"
                :disabled="isLoading"
                required
              />
              <span v-if="errors.orsBurs" class="text-red-500 text-sm text-left block">
                {{ errors.orsBurs }}
              </span>
            </div>
          </template>

          <!-- Responsibility Center Code Field -->
          <template v-if="props.modalType === 'respCode'">
            <div class="space-y-2">
              <input
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                v-model="formData.responsibilityCode"
                placeholder="Enter Responsibility Center Code"
                :disabled="isLoading"
                required
              />
              <span
                v-if="errors.responsibilityCode"
                class="text-red-500 text-sm text-left block"
              >
                {{ errors.responsibilityCode }}
              </span>
            </div>
          </template>

          <div class="flex space-x-2">
            <button
              type="submit"
              class="flex-1 px-4 py-2 bg-blue-900 text-white text-base font-medium rounded-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-blue-300 disabled:cursor-not-allowed"
              :disabled="isLoading"
            >
              <span v-if="isLoading" class="flex items-center justify-center">
                <svg
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
                Adding...
              </span>
              <span v-else>Add</span>
            </button>
            <button
              type="button"
              @click="$emit('close')"
              class="flex-1 px-4 py-2 bg-gray-700 text-gray-300 text-base font-medium rounded-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 disabled:bg-gray-200 disabled:cursor-not-allowed"
              :disabled="isLoading"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  modalType: {
    type: String,
    required: true,
  },
});

const emit = defineEmits(["close", "add"]);

const formData = ref({
  orsBurs: "",
  responsibilityCode: "",
});

const isLoading = ref(false);
const error = ref(null);
const success = ref(null);
const errors = ref({});

const getModalTitle = computed(() => {
  const titles = {
    orsBurs: "ORS/BURS Number",
    respCode: "Responsibility Center Code",
  };
  return titles[props.modalType] || "Item";
});

const resetMessages = () => {
  error.value = null;
  success.value = null;
  errors.value = {};
};

const resetForm = () => {
  formData.value.orsBurs = "";
  formData.value.responsibilityCode = "";
  resetMessages();
};

const handleValidationErrors = (errorResponse) => {
  if (errorResponse.response?.data?.errors) {
    errors.value = errorResponse.response.data.errors;
    error.value = "Please correct the errors below.";
  } else {
    error.value =
      errorResponse.response?.data?.message || "An error occurred while adding the item.";
  }
};

const addItem = async () => {
  resetMessages();

  try {
    isLoading.value = true;

    // Get the correct field based on modal type
    const fieldKey = props.modalType === "orsBurs" ? "orsBurs" : "responsibilityCode";
    const value = formData.value[fieldKey]?.trim();

    if (!value) {
      throw new Error(`${getModalTitle.value} is required`);
    }

    // Create payload with the single field value
    const payload = { [fieldKey]: value };

    await emit("add", props.modalType, value);
    success.value = "Item added successfully!";

    setTimeout(() => {
      resetForm();
      emit("close");
    }, 1500);
  } catch (err) {
    handleValidationErrors(err);
  } finally {
    isLoading.value = false;
  }
};
</script>
