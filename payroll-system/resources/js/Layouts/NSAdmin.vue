<template>
    <div
        class="h-screen flex flex-col overflow-hidden bg-gray-100"
        :class="{ dark: isDarkMode }"
    >
        <!-- Top navigation bar -->
        <nav
            class="bg-white shadow-sm flex items-center justify-between px-4 py-2 border-b border-stone-300"
        >
            <div class="flex items-center">
                <button
                    @click="toggleSidebar"
                    class="text-gray-600 focus:outline-none mr-4"
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
                            d="M4 6h16M4 12h16M4 18h16"
                        ></path>
                    </svg>
                </button>
                <div class="flex items-center">
                    <img
                        src="/icons/main-logo.png"
                        alt="DRMD Logo"
                        class="h-8 mr-2"
                    />
                    <span class="text-xl font-semibold text-gray-800"
                        >DRMD | ADMIN
                    </span>
                </div>
            </div>
            <div class="flex items-center">
                <button
                    @click="toggleDarkMode"
                    class="text-gray-600 dark:text-gray-300 focus:outline-none mx-2 transition-colors duration-200"
                >
                    <svg
                        v-if="!isDarkMode"
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
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                        ></path>
                    </svg>
                    <svg
                        v-else
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
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                        ></path>
                    </svg>
                </button>
                <button
                    @click="toggleCalendar"
                    class="text-gray-600 focus:outline-none mx-2"
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
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        ></path>
                    </svg>
                </button>
                <button
                    @click="toggleNotifications"
                    class="text-gray-600 focus:outline-none mx-2"
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
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                        ></path>
                    </svg>
                </button>
                <button
                    @click="logout"
                    class="text-gray-600 focus:outline-none ml-2"
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
            </div>
        </nav>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside
                v-if="isSidebarOpen"
                class="bg-white w-64 flex-shrink-0 border-r overflow-y-auto rounded-tr-xl rounded-br-lg shadow-lg flex flex-col h-full"
            >
                <nav class="mt-5 flex-grow">
                    <h3
                        class="px-6 text-xs font-semibold text-gray-600 uppercase tracking-wide"
                    >
                        Home
                    </h3>
                    <a
                        href="#"
                        @click.prevent="
                            $inertia.visit(route('admin.dashboard'))
                        "
                        class="flex items-center px-6 py-2 text-gray-700 hover:bg-gray-100"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            ></path>
                        </svg>
                        Admin Dashboard
                    </a>
                    <div class="mt-4">
                        <h3
                            class="px-6 text-xs font-semibold text-gray-600 uppercase tracking-wide"
                        >
                            Main Menu
                        </h3>

                        <!-- Create Function -->
                        <button
                            @click="showCreateUserForm = true"
                            class="flex items-center w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
                        >
                            <svg
                                class="w-5 h-5 mr-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                ></path>
                            </svg>
                            Create
                        </button>

                        <!-- Update Function -->
                        <button
                            @click="navigateTo('update')"
                            class="flex items-center w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
                        >
                            <svg
                                class="w-5 h-5 mr-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                ></path>
                            </svg>
                            Update
                        </button>

                        <!-- Request Function -->
                        <button
                            @click="navigateTo('request')"
                            class="flex items-center w-full px-6 py-2 text-gray-700 hover:bg-gray-100 focus:outline-none"
                        >
                            <svg
                                class="w-5 h-5 mr-3"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                ></path>
                            </svg>
                            Request
                        </button>
                    </div>
                </nav>

                <!-- Footer -->
                <div class="mt-auto p-2 border-t">
                    <p class="text-sm text-gray-600 text-center">
                        DRMD@ACE 2024
                    </p>
                </div>
            </aside>

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <slot></slot>
            </main>
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
                <form
                    @submit.prevent="submitCreateUserForm"
                    class="flex-1 flex flex-col justify-between"
                >
                    <div class="space-y-3">
                        <!-- Name fields in one line -->
                        <div class="flex space-x-2 mb-2">
                            <div
                                v-for="field in nameFields"
                                :key="field.id"
                                :class="field.class"
                            >
                                <label
                                    class="block text-gray-700 text-sm font-bold mb-1"
                                    :for="field.id"
                                >
                                    {{ field.label }}
                                </label>
                                <input
                                    v-if="field.id !== 'extension'"
                                    :class="[
                                        'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                                        { 'border-red-500': field.error },
                                    ]"
                                    :id="field.id"
                                    :type="field.type"
                                    :placeholder="field.placeholder"
                                    v-model="form[field.id]"
                                    @input="validateNameField(field.id, $event)"
                                    :required="field.required"
                                />
                                <select
                                    v-else
                                    :class="[
                                        'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                                        { 'border-red-500': field.error },
                                    ]"
                                    :id="field.id"
                                    v-model="form[field.id]"
                                >
                                    <option value="">Ext.</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                </select>
                                <p
                                    v-if="field.error"
                                    class="text-red-500 text-xs italic"
                                >
                                    {{ field.error }}
                                </p>
                            </div>
                        </div>

                        <!-- Other form fields -->
                        <div
                            v-for="field in otherFields"
                            :key="field.id"
                            class="mb-2"
                        >
                            <label
                                class="block text-gray-700 text-sm font-bold mb-1"
                                :for="field.id"
                            >
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
                                <svg
                                    v-if="field.type === 'email'"
                                    class="h-5 w-5 text-gray-500 absolute right-3 top-1/2 transform -translate-y-1/2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    ></path>
                                </svg>
                                <div v-else class="relative">
                                    <input
                                        :class="[
                                            'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                                            { 'border-red-500': field.error },
                                        ]"
                                        :id="field.id"
                                        :type="
                                            field.showPassword
                                                ? 'text'
                                                : 'password'
                                        "
                                        :placeholder="field.placeholder"
                                        v-model="form[field.id]"
                                        required
                                    />
                                    <button
                                        type="button"
                                        @click="
                                            togglePasswordVisibility(field.id)
                                        "
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
                            <p
                                v-if="field.error"
                                class="text-red-500 text-xs italic"
                            >
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
    </div>
</template>

<script>
import { ref, reactive } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";

export default {
    name: "Layout",
    setup() {
        const isDarkMode = ref(false);
        const isSidebarOpen = ref(true);
        const showCreateUserForm = ref(false);

        const form = useForm({
            firstName: "",
            middleName: "",
            lastName: "",
            extension: "",
            email: "",
            password: "",
            password_confirmation: "",
        });

        const nameFields = reactive([
            {
                id: "firstName",
                label: "First Name",
                type: "text",
                placeholder: "First name",
                error: "",
                class: "w-1/3",
                required: true,
            },
            {
                id: "middleName",
                label: "Middle Name",
                type: "text",
                placeholder: "Middle name",
                error: "",
                class: "w-1/4",
                required: false,
            },
            {
                id: "lastName",
                label: "Last Name",
                type: "text",
                placeholder: "Last name",
                error: "",
                class: "w-1/3",
                required: true,
            },
            {
                id: "extension",
                label: "Ext. Name",
                type: "select",
                placeholder: "Ext.",
                error: "",
                class: "w-1/6",
                required: false,
            },
        ]);

        const otherFields = reactive([
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

        const toggleDarkMode = () => {
            isDarkMode.value = !isDarkMode.value;
            document.documentElement.classList.toggle("dark", isDarkMode.value);
        };

        const toggleSidebar = () => {
            isSidebarOpen.value = !isSidebarOpen.value;
        };

        const logout = async () => {
            try {
                await axios.post("/logout");
                window.location.href = "/login";
            } catch (error) {
                console.error("Logout failed:", error);
            }
        };

        const toggleCalendar = () => {
            // Implement calendar toggle functionality
            console.log("Toggle calendar");
        };

        const toggleNotifications = () => {
            // Implement notifications toggle functionality
            console.log("Toggle notifications");
        };

        const navigateTo = (route) => {
            if (route === "update") {
                window.location.href = "/admin/update";
            } else if (route === "request") {
                window.location.href = "/admin/request";
            }
        };

        const validateNameField = (fieldId, event) => {
            const value = event.target.value;
            form[fieldId] = value.replace(/\d/g, "");
        };

        const togglePasswordVisibility = (fieldId) => {
            const field = otherFields.find((f) => f.id === fieldId);
            if (field) {
                field.showPassword = !field.showPassword;
            }
        };

        const validatePassword = (password) => {
            const regex =
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/;
            return regex.test(password);
        };

        const submitCreateUserForm = () => {
            let hasError = false;

            // Validate password
            if (!validatePassword(form.password)) {
                otherFields.find((f) => f.id === "password").error =
                    "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
                hasError = true;
            }

            // Check if passwords match
            if (form.password !== form.password_confirmation) {
                otherFields.find(
                    (f) => f.id === "password_confirmation"
                ).error = "Passwords do not match.";
                hasError = true;
            }

            // If there are validation errors, stop the submission
            if (hasError) {
                return;
            }

            form.post(route("register"), {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset();
                    showCreateUserForm.value = false;
                    // You may want to emit an event or update some state here to reflect the new user
                },
                onError: (errors) => {
                    [...nameFields, ...otherFields].forEach((field) => {
                        if (errors[field.id]) {
                            field.error = errors[field.id];
                        } else {
                            field.error = "";
                        }
                    });
                },
            });
        };

        return {
            isDarkMode,
            isSidebarOpen,
            showCreateUserForm,
            form,
            nameFields,
            otherFields,
            toggleDarkMode,
            toggleSidebar,
            logout,
            toggleCalendar,
            toggleNotifications,
            navigateTo,
            validateNameField,
            togglePasswordVisibility,
            submitCreateUserForm,
        };
    },
};
</script>
