<template>
  <NSAdmin>
    <div class="h-full flex flex-col px-14 overflow-hidden bg-stone-100">
      <div class="flex-1 flex flex-col overflow-hidden p-4">
        <div class="flex justify-between items-center mt-2 mb-4">
          <h1 class="text-2xl font-black text-black">User Requests</h1>
          <button
            @click="goBack"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl"
          >
            Back to Dashboard
          </button>
        </div>

        <!-- User List Page -->
        <div class="h-[85%] bg-white shadow-xl rounded-2xl overflow-hidden">
          <div class="p-4">
            <div class="overflow-x-auto">
              <table class="w-full bg-white">
                <thead>
                  <tr>
                    <th
                      class="py-2 px-4 border-b border-gray-200 text-left text-lg leading-4 font-medium text-gray-500 tracking-wider"
                    >
                      Full Name
                    </th>
                    <th
                      class="py-2 px-4 border-b border-gray-200 text-left text-lg leading-4 font-medium text-gray-500 tracking-wider"
                    >
                      Email
                    </th>
                    <th
                      class="py-2 px-4 border-b border-gray-200 text-left text-lg leading-4 font-medium text-gray-500 tracking-wider"
                    >
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="request in userRequests" :key="request.id">
                    <td class="py-2 px-4 border-b border-gray-200">
                      {{ getFullName(request) }}
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200">
                      {{ request.email }}
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200">
                      <button
                        @click="openConfirmationModal(request)"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded mr-2"
                      >
                        Create
                      </button>
                      <button
                        @click="confirmDeny(request.id)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                      >
                        Deny
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Confirmation Modal -->
        <div
          v-if="showConfirmationModal"
          class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
          <div class="bg-white p-8 rounded-lg shadow-xl text-center">
            <h2 class="text-2xl font-bold mb-4 flex items-center justify-center">
              Confirmation
            </h2>
            <p>
              Are you sure you want to create a new <br />
              user for
              {{ selectedRequest.email }}?
            </p>
            <div class="mt-6 flex justify-center space-x-4">
              <button
                @click="showConfirmationModal = false"
                class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded"
              >
                Cancel
              </button>
              <button
                @click="createUser"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
              >
                Create
              </button>
            </div>
          </div>
        </div>

        <!-- Success Message Pop-up -->
        <div
          v-if="showSuccessMessage"
          class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
          <div class="bg-white p-8 rounded-lg shadow-xl w-1/5">
            <div class="text-center">
              <svg
                class="mx-auto mb-4 w-14 h-14 text-green-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
              </svg>
              <h2 class="text-2xl font-bold mb-4 text-gray-900">Success!</h2>
              <p class="text-gray-600 mb-8">New user has been created successfully.</p>
              <button
                @click="closeSuccessMessage"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              >
                Close
              </button>
            </div>
          </div>
        </div>

        <!-- Deny Confirmation Modal -->
        <div
          v-if="showDenyConfirmation"
          class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
          <div class="bg-white p-8 rounded-lg shadow-xl">
            <h2 class="text-2xl font-bold mb-4 flex items-center justify-center">
              Confirmation
            </h2>
            <p>Are you sure you want to deny this request?</p>
            <div class="mt-6 flex justify-center space-x-4">
              <button
                @click="showDenyConfirmation = false"
                class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded"
              >
                Cancel
              </button>
              <button
                @click="denyRequest"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
              >
                Confirm
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </NSAdmin>
</template>

<script>
import NSAdmin from "@/Layouts/NSAdmin.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

export default {
  components: {
    NSAdmin,
  },
  setup() {
    const userRequests = ref([]);
    const showConfirmationModal = ref(false);
    const showSuccessMessage = ref(false);
    const showDenyConfirmation = ref(false);
    const selectedRequestId = ref(null);
    const selectedRequest = ref({});
    const isUserMenuOpen = ref(false);

    const fetchUserRequests = () => {
      try {
        const requests = JSON.parse(localStorage.getItem("userRequests") || "[]");
        userRequests.value = requests;
      } catch (error) {
        console.error("Error fetching user requests:", error);
        alert("Failed to fetch user requests");
      }
    };

    const toggleUserMenu = () => {
      isUserMenuOpen.value = !isUserMenuOpen.value;
    };

    const logout = async () => {
      try {
        await axios.post("/admin/logout");
        window.location.href = "/login";
      } catch (error) {
        console.error("Logout failed:", error);
      }
    };

    const getFullName = (request) => {
      return `${request.firstName} ${request.middleName} ${request.lastName} ${
        request.nameExt || ""
      }`.trim();
    };

    const openConfirmationModal = (request) => {
      selectedRequest.value = request;
      showConfirmationModal.value = true;
    };

    const createUser = async () => {
      try {
        const response = await axios.post("/register-request", {
          firstName: selectedRequest.value.firstName,
          lastName: selectedRequest.value.lastName,
          middleName: selectedRequest.value.middleName || "",
          nameExt: selectedRequest.value.nameExt || "",
          email: selectedRequest.value.email,
          password: selectedRequest.value.password,
          password_confirmation: selectedRequest.value.password,
        });

        if (response.data.success) {
          // Remove the request from the list
          removeRequestFromList(selectedRequest.value.id);

          showConfirmationModal.value = false;
          showSuccessMessage.value = true;

          // Redirect back to dashboard after a short delay
          setTimeout(() => {
            goBack();
          }, 2000);
        } else {
          throw new Error(response.data.message || "User creation failed");
        }
      } catch (error) {
        console.error("Error creating user:", error);
        let errorMessage = "Failed to create user";
        if (error.response && error.response.data && error.response.data.message) {
          errorMessage = error.response.data.message;
        } else if (error.message) {
          errorMessage = error.message;
        }
        alert(errorMessage);
      }
    };

    const removeRequestFromList = (requestId) => {
      const updatedRequests = userRequests.value.filter((req) => req.id !== requestId);
      userRequests.value = updatedRequests;
      localStorage.setItem("userRequests", JSON.stringify(updatedRequests));
    };

    const confirmDeny = (requestId) => {
      selectedRequestId.value = requestId;
      showDenyConfirmation.value = true;
    };

    const denyRequest = () => {
      if (selectedRequestId.value !== null) {
        removeRequestFromList(selectedRequestId.value);
        showDenyConfirmation.value = false;
      }
    };

    const closeSuccessMessage = () => {
      showSuccessMessage.value = false;
    };

    const goBack = () => {
      window.location.href = "/admin/dashboard";
    };

    onMounted(() => {
      fetchUserRequests();
    });

    return {
      userRequests,
      showConfirmationModal,
      showSuccessMessage,
      showDenyConfirmation,
      selectedRequestId,
      selectedRequest,
      isUserMenuOpen,
      toggleUserMenu,
      logout,
      getFullName,
      openConfirmationModal,
      createUser,
      confirmDeny,
      denyRequest,
      closeSuccessMessage,
      goBack,
    };
  },
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");

.font-inter {
  font-family: "Inter", sans-serif;
}
</style>
