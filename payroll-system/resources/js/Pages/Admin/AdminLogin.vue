<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const passwordVisible = ref(false);
const loginError = ref<string | null>(null);
const route = window.route;

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const hasError = computed(() => !!loginError.value);

function togglePasswordVisibility() {
    passwordVisible.value = !passwordVisible.value;
}

const submit = () => {
    loginError.value = null;
    form.post(route("admin.login"), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            window.location.href = route("admin.dashboard");
        },
        onError: (errors) => {
            console.error("Login failed", errors);
            loginError.value =
                "Login failed. Please check your credentials and try again.";
        },
        onFinish: () => {
            form.reset("password");
        },
    });
};
</script>

<template>
    <Head title="Admin Login" />
    <div
        class="relative flex-grow flex items-center justify-center overflow-hidden min-h-screen"
    >
        <img
            id="background"
            class="absolute inset-0 w-full h-screen bg-cover"
            src="icons/login.png"
        />
        <div
            class="absolute inset-0 bg-gradient-to-tr from-yellow-600 to-blue-700 mix-blend-overlay"
        ></div>
        <div
            class="relative bg-white bg-opacity-20 backdrop-blur-2xl w-[75%] px-6 mx-auto flex flex-col items-center justify-center rounded-3xl"
        >
            <main class="flex-grow flex items-center justify-center w-full">
                <header class="m-1 flex-col w-1/2">
                    <img
                        id="dswdlogo"
                        class="mx-auto w-full -mt-4 pt-4 flex"
                        src="icons/logo.png"
                        alt="DSWD Logo"
                    />
                    <h1 class="mt-1 mb-4 text-white font-black text-xl mr-40">
                        Emergency Cash Transfer <br />
                        Beneficiary Payroll System - Admin
                    </h1>
                </header>
                <form @submit.prevent="submit" class="w-1/2">
                    <div
                        class="mb-6 mt-16 text-white relative flex justify-center"
                    >
                        <div class="relative w-[70%]">
                            <div
                                v-if="loginError"
                                class="w-[100%] text-red-500 absolute text-sm top-1/2 -translate-y-1/2 py-1 pl-2 rounded-xl pointer-events-none -mt-9"
                            >
                                {{ loginError }}
                            </div>
                            <input
                                placeholder="Enter your email"
                                type="email"
                                v-model="form.email"
                                id="email"
                                name="email"
                                :class="[
                                    'w-full px-3 py-1.5 pr-10 border-y-2 border-x-2 rounded-full focus:border-blue-500 bg-transparent text-white placeholder-white',
                                    {
                                        'border-red-500': hasError,
                                        'border-white': !hasError,
                                    },
                                ]"
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
                    <div class="mb-4 text-white relative flex justify-center">
                        <div class="relative w-[70%]">
                            <input
                                placeholder="Enter your password"
                                :type="passwordVisible ? 'text' : 'password'"
                                v-model="form.password"
                                id="password"
                                name="password"
                                :class="[
                                    'w-full px-3 py-1.5 pr-10 border-y-2 border-x-2 rounded-full focus:border-blue-500 bg-transparent text-white placeholder-white',
                                    {
                                        'border-red-500': hasError,
                                        'border-white': !hasError,
                                    },
                                ]"
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
                            class="-mt-1 w-1/3 bg-white text-black font-black py-1.5 rounded-full hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out focus:outline-none"
                        >
                            Log In
                        </button>
                        <div class="mt-2">
                            <Link
                                :href="route('admin.register')"
                                class="text-white px-2 text-base font-thin mt-2 underline hover:text-blue-700"
                                >Register Admin</Link
                            >
                            <Link
                                :href="route('password.request')"
                                class="text-white px-2 text-base font-thin mt-2 mb-4 underline hover:text-blue-700"
                                >Forgot Password</Link
                            >
                        </div>
                        <Link
                            :href="route('login')"
                            class="text-white text-base font-thin mt-1 mb-4 underline hover:text-blue-700"
                            >User Login</Link
                        >
                    </div>
                </form>
            </main>
        </div>
        <footer
            class="w-full text-center text-sm text-white/70 py-2 absolute bottom-0 bg-gradient-to-r from-indigo-900 ..."
        >
            Disaster Response and Management Division - 2024
        </footer>
    </div>
</template>
