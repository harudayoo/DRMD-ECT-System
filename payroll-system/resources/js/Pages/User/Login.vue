<script setup>
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";

const passwordVisible = ref(false);
const confirmPasswordVisible = ref(false);
const loginError = ref(null);

const togglePasswordVisibility = (field) => {
    if (field === "password") {
        passwordVisible.value = !passwordVisible.value;
    } else if (field === "confirmPassword") {
        confirmPasswordVisible.value = !confirmPasswordVisible.value;
    }
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
                // Redirect to OTP page
                window.location.href = route("otp.show");
            } else {
                loginError.value =
                    "Login failed. Please check your credentials.";
            }
        },
        onError: (errors) => {
            console.error("Login failed", errors);
            loginError.value = "Login failed. Please check your credentials.";
        },
    });
};

const showConfirmation = ref(false);
const showFailedModal = ref(false);

const showNotificationForm = ref(false);
form.reset({
    firstName: "",
    middleName: "",
    lastName: "",
    extension: "",
    email: "",
    password: "",
    password_confirmation: "",
});

//User Registration
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
        placeholder: "Middle",
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

const validatePassword = (password) => {
    const regex =
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/;
    return regex.test(password);
};

const openNotificationForm = () => {
    showNotificationForm.value = true;
};

const cancelRequest = () => {
    showNotificationForm.value = false;
    form.reset();
};

const sendRequest = () => {
    let hasError = false;

    // Validate password
    if (!validatePassword(form.password)) {
        otherFields.value.find((f) => f.id === "password").error =
            "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
        hasError = true;
    }

    // Check if passwords match
    if (form.password !== form.password_confirmation) {
        otherFields.value.find((f) => f.id === "password_confirmation").error =
            "Passwords do not match.";
        hasError = true;
    }

    // If there are validation errors, stop the submission
    if (hasError) {
        form.reset(); // Reset the form fields on validation failure
        return;
    }

    // Existing sendRequest logic
    try {
        const requests = JSON.parse(
            localStorage.getItem("userRequests") || "[]"
        );
        requests.push({
            id: Date.now(),
            firstName: form.firstName,
            middleName: form.middleName,
            lastName: form.lastName,
            nameExt: form.extension,
            email: form.email,
            password: form.password,
        });
        localStorage.setItem("userRequests", JSON.stringify(requests));

        showConfirmation.value = true;
        showNotificationForm.value = false;
    } catch (error) {
        console.error("Error sending request:", error);
        showFailedModal.value = true;
    } finally {
        // Always reset the form fields after submission
        form.reset();

        // Set a timeout to reset the form again after 5 seconds (5000 milliseconds)
        setTimeout(() => {
            form.reset();
        }, 5000);
    }
};

const closeConfirmation = () => {
    showConfirmation.value = false;
};

const closeFailedModal = () => {
    showFailedModal.value = false;
};
</script>

<template>
    <div class="relative w-screen h-screen overflow-hidden">
        <!-- Background image -->
        <img
            src="/icons/login.png"
            alt="login-image"
            class="absolute inset-0 w-full h-screen bg-cover"
        />
        <!-- Gradient background -->
        <div
            class="absolute inset-0 bg-gradient-to-tr from-yellow-600 to-blue-700 mix-blend-overlay"
        ></div>
        <!-- Login box -->
        <div
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-opacity-30 bg-white backdrop-blur-2xl p-6 rounded-3xl shadow-lg w-[25%] flex flex-col items-center justify-center"
        >
            <img
                src="/icons/logo.jpg"
                alt="form-bg"
                class="mx-auto w-full -mt-1"
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
                            @click="togglePasswordVisibility('password')"
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
                    User Request
                </a>
                <a
                    :href="route('password.request')"
                    class="text-white text-sm font-thin mt-2 underline hover:text-blue-700"
                    >Forgot Password?</a
                >
            </div>
            <a
                :href="route('admin.register')"
                class="text-white text-sm font-thin mt-1 block text-center underline hover:text-blue-700"
            >
                Register Admin
            </a>
        </div>

        <!-- Request Pop-up Notification -->
        <div
            v-if="showNotificationForm"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div
                class="bg-white bg-opacity-30 backdrop-blur-xl p-8 rounded-lg shadow-2xl w-[40%] flex flex-col items-center justify-center"
            >
                <h2 class="text-2xl font-bold mb-6 text-center text-white">
                    User Registration Request
                </h2>
                <form @submit.prevent="sendRequest" class="w-full max-w-xl">
                    <div class="space-y-3">
                        <!-- Name fields in one line -->
                        <div class="flex space-x-2 mb-2">
                            <div
                                v-for="field in nameFields"
                                :key="field.id"
                                :class="field.class"
                            >
                                <label
                                    :for="field.id"
                                    class="block text-sm font-bold mb-1 text-white"
                                >
                                    {{ field.label }}
                                </label>
                                <input
                                    v-if="field.id !== 'extension'"
                                    :class="[
                                        'w-full px-3 py-1.5 pr-10 border-y-2 border-x-2 rounded-full focus:border-blue-500 bg-transparent text-white placeholder-white',
                                        {
                                            'border-red-500': field.error,
                                            'border-white': !field.error,
                                        },
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
                                        'w-full px-3 py-1.5 pr-10 border-y-2 border-x-2 rounded-full focus:border-blue-500 bg-transparent text-white',
                                        {
                                            'border-red-500': field.error,
                                            'border-white': !field.error,
                                        },
                                    ]"
                                    :id="field.id"
                                    v-model="form[field.id]"
                                >
                                    <option value="">Ext.</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                    <option value="V">V</option>
                                    <option value="VI">VI</option>
                                    <option value="VII">VII</option>
                                    <option value="VIII">VIII</option>
                                    <option value="IX">IX</option>
                                    <option value="X">X</option>
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
                                :for="field.id"
                                class="block text-sm font-bold mb-1 text-white"
                            >
                                {{ field.label }}
                            </label>
                            <div class="relative">
                                <input
                                    :class="[
                                        'w-full px-3 py-1.5 pr-10 border-y-2 border-x-2 rounded-full focus:border-blue-500 bg-transparent text-white placeholder-white',
                                        {
                                            'border-red-500': field.error,
                                            'border-white': !field.error,
                                        },
                                    ]"
                                    :id="field.id"
                                    :type="
                                        field.showPassword ? 'text' : field.type
                                    "
                                    :placeholder="field.placeholder"
                                    v-model="form[field.id]"
                                    required
                                />
                                <svg
                                    v-if="field.type === 'email'"
                                    class="h-5 w-5 text-white absolute right-3 top-1/2 transform -translate-y-1/2"
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
                                <button
                                    v-if="field.isPassword"
                                    type="button"
                                    @click="
                                        field.showPassword = !field.showPassword
                                    "
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <svg
                                        v-if="field.showPassword"
                                        class="h-5 w-5 text-white"
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
                                        class="h-5 w-5 text-white"
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
                            <p
                                v-if="field.error"
                                class="text-red-500 text-xs italic"
                            >
                                {{ field.error }}
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-4 mt-6">
                        <button
                            @click="cancelRequest"
                            type="button"
                            class="px-6 py-2 rounded-lg text-white bg-gray-400 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 transition duration-200"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-6 py-2 rounded-lg text-white bg-blue-500 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                        >
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Confirmation Modal -->
        <div
            v-if="showConfirmation"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full mx-4">
                <div class="text-center">
                    <svg
                        class="mx-auto h-12 w-12 text-green-500"
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
                    <h3 class="mt-4 text-lg font-medium text-gray-900">
                        Registration Request Sent Successfully
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Your registration request has been successfully sent to
                        the Admin. Please wait for the confirmation email.
                    </p>
                    <div class="mt-4">
                        <button
                            @click="closeConfirmation"
                            class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Failed to Send Modal -->
        <div
            v-if="showFailedModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full mx-4">
                <div class="text-center">
                    <svg
                        class="mx-auto h-12 w-12 text-red-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">
                        Failed to Send Request
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        There was an error sending your registration request.
                        Please try again later.
                    </p>
                    <div class="mt-4">
                        <button
                            @click="closeFailedModal"
                            class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <footer
            class="w-full text-center text-sm text-white/70 py-2 absolute bottom-0 bg-gradient-to-r from-indigo-900 ..."
        >
            Disaster Response and Management Division - 2024
        </footer>
    </div>
</template>

<style scoped>
select option {
    background-color: #1e1d1db3;
    color: white;
}
</style>
