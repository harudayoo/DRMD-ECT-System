<script setup>
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";

const passwordVisible = ref(false);
const loginError = ref(null);

const togglePasswordVisibility = () => {
    passwordVisible.value = !passwordVisible.value;
    const passwordInput = document.getElementById("password");
    passwordInput.type = passwordVisible.value ? "text" : "password";
};

const form = useForm({
    email: "",
    password: "",
});

const hasError = computed(() => !!loginError.value);

const submit = () => {
    loginError.value = null;
    form.post(route("login"), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            if (page.props.auth && page.props.auth.user) {
                window.location.href = route("user.dashboard");
            } else {
                loginError.value =
                    "Login successful, but user session not established. Please try again.";
            }
        },
        onError: (errors) => {
            console.error("Login failed", errors);
            loginError.value = "Login failed. Please check your credentials.";
        },
    });
};

const showNotificationForm = ref(false);
const notificationForm = useForm({});

const openNotificationForm = () => {
    showNotificationForm.value = true;
};

const submitNotificationForm = () => {
    notificationForm.post(route("register.request"), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            showNotificationForm.value = false;
            alert("Registration request sent successfully!");
        },
        onError: (errors) => {
            console.error("Registration request failed", errors);
        },
    });
};
</script>

<template>
    <div class="relative w-screen h-screen overflow-hidden">
        <!-- Gradient background -->
        <div
            class="absolute inset-0 bg-gradient-to-tr from-yellow-600 to-blue-700 mix-blend-overlay"
        ></div>
        <!-- Background image -->
        <img
            src="/icons/login.png"
            alt="login-image"
            class="w-full h-full object-cover"
        />
        <!-- Login box -->
        <div
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-opacity-30 bg-white backdrop-blur-2xl p-6 rounded-3xl shadow-lg w-[25%] flex flex-col items-center justify-center"
        >
            <img
                src="/icons/logo.png"
                alt="form-bg"
                class="mx-auto w-full -mt-2"
            />
            <form @submit.prevent="submit" class="w-full max-w-md">
                <div class="mb-4 mt-6 text-white">
                    <label for="email" class="block"></label>
                    <div class="relative">
                        <div
                            v-if="loginError"
                            class="w-[100%] text-red-500 absolute text-sm top-1/2 -translate-y-1/2 py-1 pl-2 rounded-xl pointer-events-none -mt-9"
                        >
                            {{ loginError }}
                        </div>
                        <input
                            placeholder="Email Address"
                            type="email"
                            id="email"
                            name="email"
                            :class="[
                                'w-full px-3 py-1.5 pr-10 border-y-2 border-x-2 rounded-full focus:border-blue-500 bg-transparent text-white placeholder-white',
                                {
                                    'border-red-500': hasError,
                                    'border-white': !hasError,
                                },
                            ]"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 h-6 w-6 text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                </div>
                <div class="mb-4 text-white">
                    <label for="password" class="block text-white"></label>
                    <div class="relative">
                        <input
                            placeholder="Password"
                            :type="passwordVisible ? 'text' : 'password'"
                            id="password"
                            name="password"
                            :class="[
                                'w-full px-3 py-1.5 pr-10 border-y-2 border-x-2 rounded-full focus:border-blue-500 bg-transparent text-white placeholder-white',
                                {
                                    'border-red-500': hasError,
                                    'border-white': !hasError,
                                },
                            ]"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                        <button
                            type="button"
                            @click="togglePasswordVisibility"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white focus:outline-none"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    v-if="passwordVisible"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                                <path
                                    v-if="passwordVisible"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                />
                                <path
                                    v-if="!passwordVisible"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="mt-1.5 w-1/2 bg-white text-black font-black content-center py-1.5 rounded-full hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out focus:outline-none text-lg"
                    >
                        Log In
                    </button>
                </div>
            </form>
            <div class="mt-2 text-center">
                <a
                    @click.prevent="openNotificationForm"
                    class="text-white px-2 text-sm font-thin mt-2 underline hover:text-blue-700"
                >
                    Register User
                </a>
                <a
                    :href="route('password.request')"
                    class="text-white text-sm font-thin mt-2 underline hover:text-blue-700"
                    >Forgot Password?</a
                >
            </div>
            <a
                href="/admin"
                class="text-white text-sm font-thin mt-1 block text-center underline hover:text-blue-700"
            >
                Admin Login
            </a>
        </div>

        <!-- Registration Pop-up Notification -->
        <div
            v-if="showNotificationForm"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div
                class="bg-white backdrop-blur-sm bg-opacity-80 p-6 rounded-lg shadow-lg w-96"
            >
                <h2 class="text-xl font-bold mb-4 text-center text-gray-800">
                    For User Regestration
                </h2>
                <p class="mb-4 text-sm text-gray-600 text-center">
                    Kindly contact your administrator for the registration of
                    your account, Thank you!
                </p>
                <button
                    @click="showNotificationForm = false"
                    type="button"
                    class="bg-blue-400 text-black-700 px-4 py-2 ml-28 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
                >
                    Thank You
                </button>
            </div>
        </div>
        <footer
            class="w-full text-center text-sm text-white/70 py-2 absolute bottom-0 bg-gradient-to-r from-indigo-900 ..."
        >
            Disaster Response and Management Division - 2024
        </footer>
    </div>
</template>
