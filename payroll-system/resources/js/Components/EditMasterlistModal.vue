<template>
  <div class="fixed z-10 inset-0 overflow-y-auto">
    <div
      class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
    >
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"
        >&#8203;</span
      >

      <div
        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
      >
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Edit Beneficiary
              </h3>
              <div class="mt-2">
                <form @submit.prevent="saveBeneficiary">
                  <div class="mb-4">
                    <label for="lastName" class="block text-sm font-medium text-gray-700"
                      >Last Name</label
                    >
                    <input
                      type="text"
                      name="lastName"
                      id="lastName"
                      v-model="editedBeneficiary.lastName"
                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    />
                  </div>
                  <div class="mb-4">
                    <label for="firstName" class="block text-sm font-medium text-gray-700"
                      >First Name</label
                    >
                    <input
                      type="text"
                      name="firstName"
                      id="firstName"
                      v-model="editedBeneficiary.firstName"
                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    />
                  </div>
                  <div class="mb-4">
                    <label
                      for="middleName"
                      class="block text-sm font-medium text-gray-700"
                      >Middle Name</label
                    >
                    <input
                      type="text"
                      name="middleName"
                      id="middleName"
                      v-model="editedBeneficiary.middleName"
                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    />
                  </div>
                  <div class="mb-4">
                    <label
                      for="extensionName"
                      class="block text-sm font-medium text-gray-700"
                      >Extension Name</label
                    >
                    <input
                      type="text"
                      name="extensionName"
                      id="extensionName"
                      v-model="editedBeneficiary.extensionName"
                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    />
                  </div>
                  <div class="mb-4">
                    <label
                      for="dateOfBirth"
                      class="block text-sm font-medium text-gray-700"
                      >Date of Birth</label
                    >
                    <input
                      type="date"
                      name="dateOfBirth"
                      id="dateOfBirth"
                      v-model="editedBeneficiary.dateOfBirth"
                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    />
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            type="button"
            @click="saveBeneficiary"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Save
          </button>
          <button
            type="button"
            @click="$emit('close')"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";

const props = defineProps<{
  beneficiary: any;
}>();

const editedBeneficiary = ref({ ...props.beneficiary });

onMounted(() => {
  editedBeneficiary.value = { ...props.beneficiary };
});

const emit = defineEmits(["close", "save"]);

const saveBeneficiary = () => {
  emit("save", editedBeneficiary.value);
};
</script>
