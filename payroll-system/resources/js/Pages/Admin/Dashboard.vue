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
            class="w-6 h-6"
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

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden p-4">
      <!-- Header -->
      <header class="mb-2 ml-1">
        <h1 class="text-3xl font-black text-black">Admin</h1>
      </header>

      <!-- Content rectangle -->
      <div class="flex-1 bg-grey-100 shadow-2xl rounded-lg flex overflow-hidden">
        <!-- Left side buttons -->
        <div class="w-64 p-4 flex flex-col space-y-4 rounded-xl bg-gray-100">
          <button
            @click="showCreateUserForm = true"
            class="shadow-xl border-gray-300 border-2 text-xl text-black py-3 px-4 rounded-xl hover:bg-gray-600 hover:text-white transition-colors duration-200"
          >
            Create User
          </button>
          <button
            @click="navigateTo('update')"
            class="shadow-xl border-gray-300 border-2 text-xl text-black py-3 px-4 rounded-xl hover:bg-gray-600 hover:text-white transition-colors duration-200"
          >
            Update User
          </button>
          <button
            @click="navigateTo('request')"
            class="shadow-xl border-gray-300 border-2 text-xl text-black py-3 px-4 rounded-xl hover:bg-gray-600 hover:text-white transition-colors duration-200"
          >
            Request
          </button>
        </div>
        <!-- Chart area -->
        <div class="w-4/5 p-4">
          <canvas ref="myChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Create User Form Pop-up -->
    <div
      v-if="showCreateUserForm"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
    >
      <div
        class="bg-white p-8 rounded-lg shadow-xl w-2/5 max-h-[90vh] relative flex flex-col overflow-y-auto"
      >
        <button
          @click="showCreateUserForm = false"
          class="absolute top-3 right-4 text-gray-600 hover:text-gray-800"
        >
          <svg
            class="w-7 h-7"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
        </button>
        <h2 class="text-2xl font-bold mb-4 text-center">Create User</h2>
        <form @submit.prevent="submit" class="flex-1 flex flex-col justify-between">
          <div class="space-y-3">
            <div v-for="field in formFields" :key="field.id" class="mb-2">
              <label class="block text-gray-700 text-sm font-bold mb-1" :for="field.id">
                {{ field.label }}
              </label>
              <div class="relative">
                <input
                  v-if="!field.isPassword"
                  :class="[
                    'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                    { 'border-red-500': field.error },
                  ]"
                  :id="field.id"
                  :type="field.type"
                  :placeholder="field.placeholder"
                  v-model="form[field.id]"
                  required
                />
                <div v-else class="relative">
                  <input
                    :class="[
                      'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                      { 'border-red-500': field.error },
                    ]"
                    :id="field.id"
                    :type="field.showPassword ? 'text' : 'password'"
                    :placeholder="field.placeholder"
                    v-model="form[field.id]"
                    required
                  />
                  <button
                    type="button"
                    @click="togglePasswordVisibility(field.id)"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                  >
                    <svg
                      v-if="field.showPassword"
                      class="h-5 w-5 text-gray-500"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                      ></path>
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                      ></path>
                    </svg>
                    <svg
                      v-else
                      class="h-5 w-5 text-gray-500"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                      ></path>
                    </svg>
                  </button>
                </div>
              </div>
              <p v-if="field.error" class="text-red-500 text-xs italic">
                {{ field.error }}
              </p>
            </div>
          </div>
          <div class="flex items-center justify-center mt-6">
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
              type="submit"
              :disabled="form.processing"
            >
              Create Account
            </button>
          </div>
        </form>
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
  </div>
</template>
<script>
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";

export default {
  name: "Dashboard",
  components: {
    Link,
  },
  props: {
    userData: {
      type: Array,
      default: () => [],
    },
  },
  setup(props) {
    const isMenuOpen = ref(false);
    const isUserMenuOpen = ref(false);
    const showCreateUserForm = ref(false);
    const showSuccessMessage = ref(false);
    const myChart = ref(null);

    const toggleMenu = () => {
      isMenuOpen.value = !isMenuOpen.value;
    };

    const toggleUserMenu = () => {
      isUserMenuOpen.value = !isUserMenuOpen.value;
    };

    // Function to clear cookies
    const clearCookies = () => {
      const cookies = document.cookie.split(";");
      cookies.forEach((cookie) => {
        const [name] = cookie.split("=");
        document.cookie = `${name.trim()}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/;`;
      });
    };

    const logout = async () => {
      console.log("Logout Successful. Redirecting to login.");
      window.location.href = "/login";
    };

    const form = useForm({
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    });

    const formFields = ref([
      {
        id: "name",
        label: "Full Name",
        type: "text",
        placeholder: "Enter your full name",
        error: "",
      },
      {
        id: "email",
        label: "Email Address",
        type: "email",
        placeholder: "Enter your email address",
        error: "",
      },
      {
        id: "password",
        label: "Password",
        type: "password",
        placeholder: "Enter your password",
        error: "",
        isPassword: true,
        showPassword: false,
      },
      {
        id: "password_confirmation",
        label: "Confirm Password",
        type: "password",
        placeholder: "Confirm your password",
        error: "",
        isPassword: true,
        showPassword: false,
      },
    ]);

    const togglePasswordVisibility = (fieldId) => {
      const field = formFields.value.find((f) => f.id === fieldId);
      if (field) {
        field.showPassword = !field.showPassword;
      }
    };

    const submit = () => {
      form.post(route("register"), {
        preserveScroll: true,
        onSuccess: () => {
          form.reset("password", "password_confirmation");
          showCreateUserForm.value = false;
          showSuccessMessage.value = true;
        },
        onError: (errors) => {
          Object.keys(errors).forEach((key) => {
            const field = formFields.value.find((f) => f.id === key);
            if (field) {
              field.error = errors[key];
            }
          });
        },
      });
    };

    const closeSuccessMessage = () => {
      showSuccessMessage.value = false;
    };

    onMounted(() => {
      const ctx = myChart.value.getContext("2d");

      const data = {
        labels: props.userData.map((user) => user.name),
        datasets: [
          {
            label: "Number of Logins",
            backgroundColor: "rgb(255, 102, 178)",
            borderColor: "rgb(255, 102, 178)",
            data: props.userData.map((user) => user.loginNum),
            fill: false,
            tension: 0.1,
          },
        ],
      };

      const config = {
        type: "line",
        data: data,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: "Number of Logins",
              },
            },
            x: {
              title: {
                display: true,
                text: "Users",
              },
            },
          },
        },
      };

      if (props.userData.length > 0) {
        new Chart(ctx, config);
      } else {
        ctx.font = "20px Arial";
        ctx.fillText("No user data available", 10, 50);
      }
    });

    return {
      isMenuOpen,
      isUserMenuOpen,
      showCreateUserForm,
      showSuccessMessage,
      myChart,
      toggleMenu,
      toggleUserMenu,
      logout,
      form,
      formFields,
      togglePasswordVisibility,
      submit,
      closeSuccessMessage,
    };
  },
  methods: {
    navigateTo(route) {
      if (route === "update") {
        this.$inertia.visit("/admin/update");
      } else if (route === "request") {
        this.$inertia.visit("/admin/request");
      }
    },
  },
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");

.font-inter {
  font-family: "Inter", sans-serif;
}
</style>
