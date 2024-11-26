<template>
    <div class="h-screen flex flex-col overflow-hidden bg-gray-100">
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
                    @click="toggleCalendar"
                    class="relative text-gray-600 focus:outline-none mx-2 p-2 rounded-full hover:text-blue-900 hover:bg-gray-300 transition-all duration-200"
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
                <!--
                <button
                    @click="toggleNotifications"
                    class="relative text-gray-600 focus:outline-none mx-2 p-2 rounded-full hover:text-blue-900 hover:bg-gray-300 transition-all duration-200"
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
                -->
                <button
                    @click="toggleProfileMenu"
                    class="relative text-gray-600 focus:outline-none mx-2 p-2 rounded-full hover:text-blue-900 hover:bg-gray-300 transition-all duration-200"
                >
                    <div class="relative">
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
                        <ProfileExtension
                            :show="showProfileMenu"
                            @close="showProfileMenu = false"
                        />
                    </div>
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
                        <!-- Name fields remain the same except for extension/nameExt change -->
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
                                    v-if="field.id !== 'nameExt'"
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

                        <!-- Updated password fields with proper autocomplete -->
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
                                    :autocomplete="field.autocomplete"
                                    required
                                />
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
                                        :autocomplete="field.autocomplete"
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
                            class="bg-blue-900 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
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
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                v-if="showSuccessMessage"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                @click="closeSuccessMessageOnBackdrop"
            >
                <div
                    class="bg-white p-8 rounded-lg shadow-xl w-96 transform transition-all"
                    @click.stop
                >
                    <div class="text-center">
                        <div
                            class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-100 mb-4"
                        >
                            <svg
                                class="h-8 w-8 text-green-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-2 text-gray-900">
                            Success!
                        </h2>
                        <p class="text-gray-600 mb-6">
                            New user has been created successfully.
                        </p>
                        <div class="flex justify-center space-x-4">
                            <button
                                @click="closeSuccessMessage"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200"
                            >
                                Close
                            </button>
                            <button
                                @click="createAnotherUser"
                                class="bg-blue-900 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                            >
                                Create Another
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <Calendar :show="showCalendar" @close="showCalendar = false" />
    </div>
</template>

<script>
import { ref, reactive, onBeforeUnmount, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import Calendar from "@/Components/Calendar.vue";
import ProfileExtension from "@/Components/ProfileExtension.vue";

export default {
    name: "Layout",
    components: {
        Calendar: Calendar,
        ProfileExtension: ProfileExtension,
    },
    setup() {
        const isSidebarOpen = ref(true);
        const showCreateUserForm = ref(false);
        const showSuccessMessage = ref(false);
        const successMessageTimer = ref(null);
        const showCalendar = ref(false);
        const showProfileMenu = ref(false);

        const form = useForm({
            firstName: "",
            middleName: "",
            lastName: "",
            nameExt: "",
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
                id: "nameExt",
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
                autocomplete: "email",
            },
            {
                id: "password",
                label: "Password",
                type: "password",
                placeholder: "Enter your password",
                error: "",
                isPassword: true,
                showPassword: false,
                autocomplete: "new-password",
            },
            {
                id: "password_confirmation",
                label: "Confirm Password",
                type: "password",
                placeholder: "Confirm your password",
                error: "",
                isPassword: true,
                showPassword: false,
                autocomplete: "new-password",
            },
        ]);

        // Toggle functions
        const toggleSidebar = () => {
            isSidebarOpen.value = !isSidebarOpen.value;
        };

        const toggleCalendar = () => {
            showCalendar.value = !showCalendar.value;
        };

        const toggleProfileMenu = () => {
            showProfileMenu.value = !showProfileMenu.value;
        };

        const togglePasswordVisibility = (fieldId) => {
            const field = otherFields.find((f) => f.id === fieldId);
            if (field) {
                field.showPassword = !field.showPassword;
            }
        };

        // Navigation and logout
        const navigateTo = (route) => {
            if (route === "update") {
                window.location.href = "/admin/update";
            } else if (route === "request") {
                window.location.href = "/admin/request";
            }
        };

        const logout = async () => {
            try {
                await axios.post(
                    "/",
                    {},
                    {
                        headers: {
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    }
                );
                window.location.href = "/login";
            } catch (error) {
                console.error("Logout failed:", error);
                if (error.response && error.response.status === 401) {
                    window.location.href = "/login";
                }
            }
        };

        // Form validation and handling
        const validateNameField = (fieldId, event) => {
            const value = event.target.value;
            form[fieldId] = value.replace(/\d/g, "");
        };

        const validatePassword = (password) => {
            const regex =
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/;
            return regex.test(password);
        };

        // Success message handlers
        const displaySuccessMessage = () => {
            showSuccessMessage.value = true;

            if (successMessageTimer.value) {
                clearTimeout(successMessageTimer.value);
            }

            successMessageTimer.value = setTimeout(() => {
                closeSuccessMessage();
            }, 5000);
        };

        const closeSuccessMessage = () => {
            showSuccessMessage.value = false;
            if (successMessageTimer.value) {
                clearTimeout(successMessageTimer.value);
                successMessageTimer.value = null;
            }
        };

        const closeSuccessMessageOnBackdrop = (event) => {
            if (event.target === event.currentTarget) {
                closeSuccessMessage();
            }
        };

        const createAnotherUser = () => {
            closeSuccessMessage();
            showCreateUserForm.value = true;
            form.reset();
            [...nameFields, ...otherFields].forEach((field) => {
                field.error = "";
            });
        };

        const submitCreateUserForm = () => {
            let hasError = false;

            // Required field validation
            nameFields.forEach((field) => {
                if (field.required && !form[field.id]) {
                    field.error = `${field.label} is required`;
                    hasError = true;
                } else {
                    field.error = "";
                }
            });

            // Email validation
            const emailField = otherFields.find((f) => f.id === "email");
            if (!form.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
                emailField.error = "Please enter a valid email address";
                hasError = true;
            } else {
                emailField.error = "";
            }

            // Password validation
            if (!validatePassword(form.password)) {
                otherFields.find((f) => f.id === "password").error =
                    "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
                hasError = true;
            } else {
                otherFields.find((f) => f.id === "password").error = "";
            }

            // Password confirmation validation
            if (form.password !== form.password_confirmation) {
                otherFields.find(
                    (f) => f.id === "password_confirmation"
                ).error = "Passwords do not match.";
                hasError = true;
            } else {
                otherFields.find(
                    (f) => f.id === "password_confirmation"
                ).error = "";
            }

            if (hasError) {
                return;
            }

            form.post(route("register"), {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset();
                    showCreateUserForm.value = false;
                    displaySuccessMessage();
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

        // Cleanup
        onBeforeUnmount(() => {
            if (successMessageTimer.value) {
                clearTimeout(successMessageTimer.value);
            }
        });

        return {
            // State
            isSidebarOpen,
            showCreateUserForm,
            showSuccessMessage,
            form,
            nameFields,
            otherFields,

            // Methods
            toggleSidebar,
            showCalendar,
            toggleCalendar,
            togglePasswordVisibility,
            navigateTo,
            logout,
            validateNameField,
            submitCreateUserForm,
            closeSuccessMessage,
            closeSuccessMessageOnBackdrop,
            createAnotherUser,
            showProfileMenu,
            toggleProfileMenu,
        };
    },
};
</script>
