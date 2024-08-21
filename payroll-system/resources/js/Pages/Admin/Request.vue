<template>
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

        <!-- Header -->
        <div class="flex-1 flex flex-col overflow-hidden p-4">
            <header class="mb-2 ml-1">
                <h1 class="text-3xl font-black text-black">Admin</h1>
            </header>

            <!-- User List Page -->
            <div
                class="flex-1 bg-gray-100 shadow-2xl rounded-lg overflow-hidden"
            >
                <div class="p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">User Requests</h2>
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
                                        Email
                                    </th>
                                    <th
                                        class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="request in userRequests"
                                    :key="request.id"
                                >
                                    <td
                                        class="py-2 px-4 border-b border-gray-200"
                                    >
                                        {{ request.email }}
                                    </td>
                                    <td
                                        class="py-2 px-4 border-b border-gray-200"
                                    >
                                        <button
                                            @click="
                                                openCreateUserForm(
                                                    request.email,
                                                    request.id
                                                )
                                            "
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded mr-2"
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
                    <h2 class="text-2xl font-bold mb-4 text-center">
                        Create User
                    </h2>
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
                                        :class="[
                                            'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                                            { 'border-red-500': field.error },
                                        ]"
                                        :id="field.id"
                                        :type="field.type"
                                        :placeholder="field.placeholder"
                                        v-model="form[field.id]"
                                        @input="
                                            validateNameField(field.id, $event)
                                        "
                                        :required="field.required"
                                    />
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
                                    <div v-else class="relative">
                                        <input
                                            :class="[
                                                'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                                                {
                                                    'border-red-500':
                                                        field.error,
                                                },
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
                                                togglePasswordVisibility(
                                                    field.id
                                                )
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
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <!-- Confirmation Modal -->
            <div
                v-if="showConfirmation"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
            >
                <div class="bg-white p-8 rounded-lg shadow-xl">
                    <h2 class="text-2xl font-bold mb-4">Confirmation</h2>
                    <p>Are you sure you want to deny this request?</p>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button
                            @click="showConfirmation = false"
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
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";

export default {
    setup() {
        const userRequests = ref([]);
        const showCreateUserForm = ref(false);
        const showSuccessMessage = ref(false);
        const showConfirmation = ref(false);
        const selectedRequestId = ref(null);
        const selectedEmail = ref("");
        const isUserMenuOpen = ref(false);
        const userCreated = ref(null);

        const form = ref({
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
                type: "text",
                placeholder: "Jr./Sr.",
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

        const fetchUserRequests = () => {
            try {
                const requests = JSON.parse(
                    localStorage.getItem("userRequests") || "[]"
                );
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

        const openCreateUserForm = (email, requestId) => {
            showCreateUserForm.value = true;
            selectedEmail.value = email;
            selectedRequestId.value = requestId;
            form.value.email = email;
        };

        const validateNameField = (fieldId, event) => {
            const value = event.target.value;
            form.value[fieldId] = value.replace(/\d/g, "");
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

        const submit = async () => {
            let hasError = false;

            if (!validatePassword(form.value.password)) {
                otherFields.value.find((f) => f.id === "password").error =
                    "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
                hasError = true;
            }

            if (form.value.password !== form.value.password_confirmation) {
                otherFields.value.find(
                    (f) => f.id === "password_confirmation"
                ).error = "Passwords do not match.";
                hasError = true;
            }

            if (hasError) {
                return;
            }

            try {
                const { data } = await axios.post("/register", form.value);
                form.value = {
                    firstName: "",
                    middleName: "",
                    lastName: "",
                    extension: "",
                    email: "",
                    password: "",
                    password_confirmation: "",
                };
                showCreateUserForm.value = false;
                showSuccessMessage.value = true;
                userCreated.value = {
                    name: `${form.value.firstName} ${form.value.lastName}`,
                    loginNum: 0,
                };
                removeRequestFromList();
            } catch (error) {
                console.error("Registration failed:", error);
                if (
                    error.response &&
                    error.response.data &&
                    error.response.data.errors
                ) {
                    const errors = error.response.data.errors;
                    [...nameFields.value, ...otherFields.value].forEach(
                        (field) => {
                            if (errors[field.id]) {
                                field.error = errors[field.id][0];
                            } else {
                                field.error = "";
                            }
                        }
                    );
                }
            }
        };

        const removeRequestFromList = () => {
            if (selectedRequestId.value !== null) {
                try {
                    const updatedRequests = userRequests.value.filter(
                        (req) => req.id !== selectedRequestId.value
                    );
                    userRequests.value = updatedRequests;
                    localStorage.setItem(
                        "userRequests",
                        JSON.stringify(updatedRequests)
                    );
                } catch (error) {
                    console.error("Error removing request from list:", error);
                    alert("Failed to remove request");
                }
            }
        };

        const confirmDeny = (requestId) => {
            selectedRequestId.value = requestId;
            showConfirmation.value = true;
        };

        const denyRequest = () => {
            if (selectedRequestId.value !== null) {
                try {
                    const updatedRequests = userRequests.value.filter(
                        (req) => req.id !== selectedRequestId.value
                    );
                    localStorage.setItem(
                        "userRequests",
                        JSON.stringify(updatedRequests)
                    );
                    userRequests.value = updatedRequests;
                    showConfirmation.value = false;
                } catch (error) {
                    console.error("Error denying request:", error);
                    alert("Failed to deny request");
                }
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
            showCreateUserForm,
            showSuccessMessage,
            showConfirmation,
            selectedRequestId,
            selectedEmail,
            isUserMenuOpen,
            form,
            nameFields,
            otherFields,
            toggleUserMenu,
            logout,
            openCreateUserForm,
            validateNameField,
            togglePasswordVisibility,
            submit,
            confirmDeny,
            denyRequest,
            closeSuccessMessage,
            goBack,
            userCreated,
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
