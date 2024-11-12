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
              :disabled="form.processing"
              @input="validateRcdName"
            />
            <div v-if="form.errors.rcdName" class="mt-2 text-red-500 text-sm">
              {{ form.errors.rcdName }}
            </div>
          </div>
          <div class="col-span-2">
            <label for="cdrID" class="block mb-2">CDR</label>
            <select
              id="cdrID"
              v-model="form.cdrID"
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
              :disabled="form.processing"
              @change="validateForm"
            >
              <option value="" disabled>Select CDR</option>
              <option v-for="cdr in sortedCdrs" :key="cdr.cdrID" :value="cdr.cdrID">
                {{ cdr.cdrID }} - {{ cdr.cdrName }}
              </option>
            </select>
            <div v-if="form.errors.cdrID" class="mt-2 text-red-500 text-sm">
              {{ form.errors.cdrID }}
            </div>
          </div>
        </div>
        <div
          v-if="form.errors.generalError"
          class="mt-2 p-2 bg-red-100 border border-red-400 text-red-700 rounded"
        >
          {{ form.errors.generalError }}
        </div>
        <div class="mt-6 flex justify-end space-x-4">
          <button
            type="button"
            @click="closeModal"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-full hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
            :disabled="form.processing"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-900 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center"
            :disabled="form.processing || !isFormValid"
          >
            <svg
              v-if="form.processing"
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
            {{ form.processing ? "Creating..." : "Create RCD" }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
  cdrs: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["close"]);
const isFormValid = ref(false);

const form = useForm({
  rcdName: "",
  cdrID: "",
});

// Sort CDRs by ID
const sortedCdrs = computed(() => {
  return [...props.cdrs].sort((a, b) => {
    return a.cdrID.localeCompare(b.cdrID);
  });
});

const validateRcdName = () => {
  if (form.rcdName.trim().length < 3) {
    form.errors.rcdName = "RCD Name must be at least 3 characters long";
  } else {
    delete form.errors.rcdName;
  }
  validateForm();
};

const validateForm = () => {
  isFormValid.value =
    form.rcdName.trim().length >= 3 &&
    form.cdrID !== "" &&
    Object.keys(form.errors).length === 0;
};

const submitRcd = () => {
  if (!isFormValid.value) return;

  form.post(route("rcd.store"), {
    preserveScroll: true,
    onSuccess: () => {
      emit("close");
      form.reset();
    },
    onError: () => {
      validateForm();
    },
  });
};

const closeModal = () => {
  form.reset();
  form.clearErrors();
  emit("close");
};
</script>
