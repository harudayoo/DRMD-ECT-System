<template>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="h-screen flex flex-col overflow-hidden bg-white">
        <!-- Top bar -->
        <nav
            class="bg-red-700 shadow-2xl flex items-center justify-end px-4 py-1"
        >
            <div class="relative">
                <button
                    @click="toggleUserMenu"
                    class="text-white focus:outline-none mt-1 hover:text-blue-700 transition-colors duration-200"
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
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
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
            <div
                class="flex-1 bg-grey-100 shadow-2xl rounded-lg flex overflow-hidden"
            >
                <!-- Left side buttons -->
                <div
                    class="w-64 p-4 flex flex-col space-y-4 rounded-xl bg-gray-100"
                >
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
                <form
                    @submit.prevent="submit"
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
                            class="bg-red-900 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
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
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">
                        Success!
                    </h2>
                    <p class="text-gray-600 mb-8">
                        New user has been created successfully.
                    </p>
                    <button
                        @click="closeSuccessMessage"
                        class="bg-green-800 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
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
import axios from "axios";

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
    emits: ["userCreated"],
    setup(props, { emit }) {
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

        const logout = async () => {
            try {
                await axios.post(route("admin.logout"));
                window.location.href = route("login");
            } catch (error) {
                console.error("Logout failed:", error);
                // Handle the error appropriately
            }
        };

        const form = useForm({
            firstName: "",
            middleName: "",
            lastName: "",
            extension: "",
            email: "",
            password: "",
            password_confirmation: "",
        });

        const nameFields = ref([
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

        const otherFields = ref([
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

        const validateNameField = (fieldId, event) => {
            const value = event.target.value;
            form[fieldId] = value.replace(/\d/g, "");
        };

        const togglePasswordVisibility = (fieldId) => {
            const field = otherFields.value.find((f) => f.id === fieldId);
            if (field) {
                field.showPassword = !field.showPassword;
            }
        };

        const validatePassword = (password) => {
            const regex =
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/;
            return regex.test(password);
        };

        const submit = () => {
            let hasError = false;

            // Validate password
            if (!validatePassword(form.password)) {
                otherFields.value.find((f) => f.id === "password").error =
                    "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
                hasError = true;
            }

            // Check if passwords match
            if (form.password !== form.password_confirmation) {
                otherFields.value.find(
                    (f) => f.id === "password_confirmation"
                ).error = "Passwords do not match.";
                hasError = true;
            }

            // If there are validation errors, stop the submission
            if (hasError) {
                return;
            }

            const submissionForm = useForm({
                firstName: form.firstName,
                middleName: form.middleName,
                lastName: form.lastName,
                nameExt: form.extension,
                email: form.email,
                password: form.password,
                password_confirmation: form.password_confirmation,
            });

            submissionForm.post(route("register"), {
                preserveScroll: true,
                onSuccess: (response) => {
                    form.reset();
                    showCreateUserForm.value = false;
                    showSuccessMessage.value = true;

                    emit("userCreated", {
                        name: `${form.firstName} ${form.lastName}`,
                        loginNum: 0,
                    });

                    updateChart();
                },
                onError: (errors) => {
                    [...nameFields.value, ...otherFields.value].forEach(
                        (field) => {
                            if (errors[field.id]) {
                                field.error = errors[field.id];
                            } else {
                                field.error = "";
                            }
                        }
                    );
                },
            });
            window.location.reload();
        };

        const updateChart = () => {
            if (myChart.value) {
                myChart.value.data.labels = props.userData.map(
                    (user) => user.name
                );
                myChart.value.data.datasets[0].data = props.userData.map(
                    (user) => user.loginNum
                );
                myChart.value.update();
            }
        };

        const closeSuccessMessage = () => {
            showSuccessMessage.value = false;
        };

        onMounted(() => {
            const ctx = myChart.value.getContext("2d");
            console.log("Dashboard mounted");

            if (props.userData.length > 0) {
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
                    type: "bar",
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

                myChart.value = new Chart(ctx, config);
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
            nameFields,
            otherFields,
            validateNameField,
            togglePasswordVisibility,
            submit,
            closeSuccessMessage,
            updateChart,
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
