<template>
  <div class="h-screen flex flex-col overflow-hidden bg-white">
    <!-- Top bar -->
    <nav class="bg-red-700 shadow-2xl flex items-center justify-between px-4 py-2">
      <button
        @click="toggleMenu"
        class="text-white focus:outline-none hover:text-blue-700 transition-colors duration-200"
      >
        <svg
          class="w-6 h-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2.5"
            d="M4 6h16M4 12h16M4 18h16"
          ></path>
        </svg>
      </button>
      <div class="relative">
        <button
          @click="toggleUserMenu"
          class="text-white focus:outline-none hover:text-blue-700 transition-colors duration-200"
        >
          <svg
            class="w-7 h-7 -mb-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
        </button>
        <div
          v-if="isUserMenuOpen"
          class="absolute right-0 w-48 bg-white rounded-md shadow-lg py-1 z-10"
        >
          <a
            href="#"
            @click="logout"
            class="block px-4 py-2 text-sm text-gray-900 hover:bg-gray-300"
            >Log out</a
          >
        </div>
      </div>
    </nav>
    <!-- Header -->
    <div class="flex-1 flex flex-col overflow-hidden p-4">
      <header class="mb-2 ml-1">
        <h1 class="text-3xl font-black text-black">Admin</h1>
      </header>
      <!-- User List Page -->
      <div class="flex-1 bg-grey-100 shadow-2xl rounded-lg overflow-hidden">
        <div class="p-4">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Logs</h2>
            <button
              @click="goBack"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Back to Dashboard
            </button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full bg-white">
              <thead>
                <tr>
                  <th
                    class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Full Name
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Username
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Gmail
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Action
                  </th>
                  <th
                    class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Time Stamp
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user, index) in registeredUsers" :key="index">
                  <td class="py-2 px-4 border-b border-gray-200">
                    {{ user.fullName }}
                  </td>
                  <td class="py-2 px-4 border-b border-gray-200">
                    {{ user.username }}
                  </td>
                  <td class="py-2 px-4 border-b border-gray-200">
                    {{ user.gmail }}
                  </td>
                  <td class="py-2 px-4 border-b border-gray-200">
                    {{ user.action }}
                  </td>
                  <td class="py-2 px-4 border-b border-gray-200">
                    {{ user.timestamp }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";

// Menu logic
const isMenuOpen = ref(false);
const isUserMenuOpen = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value;
};

const logout = () => {
  console.log("Logout clicked");
  // Add your logout logic here
  window.location.href = "/"; // This will redirect to the root URL
};

const goBack = () => {
  // Add your navigation logic here
  console.log("Go back clicked");
  // For example, you can use Vue Router to go back
  window.location.href = "/admin/dashboard";
};

// User List Page
const showUserList = ref(false);
const registeredUsers = ref([
  {
    fullName: "Jurgen Couzan",
    username: "pykka",
    gmail: "pykka@sample.com",
    action: "Logged in",
    timestamp: new Date().toLocaleString(),
  },
  {
    fullName: "Harold Daniel",
    username: "huhrude",
    gmail: "huhrude@sample.com",
    action: "Add Masterlist",
    timestamp: new Date().toLocaleString(),
  },
  {
    fullName: "Jade Enopia",
    username: "mashiron",
    gmail: "mashiron@sample.com",
    action: "Print Payroll",
    timestamp: new Date().toLocaleString(),
  },
  {
    fullName: "Perrie Edwards",
    username: "perrie",
    gmail: "perrie@sample.com",
    action: "Checked Municipality Dashboards",
    timestamp: new Date().toLocaleString(),
  },
  {
    fullName: "Leigh-Anne Pinnock",
    username: "leigh",
    gmail: "Print CDR",
    action: "Logged in",
    timestamp: new Date().toLocaleString(),
  },
  {
    fullName: "Chloe Moretz",
    username: "kloh",
    gmail: "kloh@sample.com",
    action: "Logged in",
    timestamp: new Date().toLocaleString(),
  },
  {
    fullName: "Amanda Seyfried",
    username: "sophie",
    gmail: "sophie@sample.com",
    action: "Print RCD",
    timestamp: new Date().toLocaleString(),
  },
  {
    fullName: "Hustina Ligaya",
    username: "kiri",
    gmail: "kiri@sample.com",
    action: "Logged in",
    timestamp: new Date().toLocaleString(),
  },
  // Add more mock data as needed
]);

onMounted(() => {
  // Fetch user logs from the backend if available
  // Example: fetch('http://localhost:3000/logs').then(response => response.json()).then(data => registeredUsers.value = data);
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");

.font-inter {
  font-family: "Inter", sans-serif;
}
</style>
