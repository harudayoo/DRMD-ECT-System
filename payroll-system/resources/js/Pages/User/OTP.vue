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
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-opacity-30 bg-white backdrop-blur-2xl p-6 rounded-3xl shadow-lg w-3/12 h-2/4"
        >
            <img
                src="/icons/logo.jpg"
                alt="form-bg"
                class="mx-auto w-full -mt-2"
            />
            <p class="text-center mt-2 text-base font-medium">
                Please enter the 6-digit authentication code we sent to your
                email address.
            </p>
            <!-- Display error message -->
            <transition name="fade">
                <p v-if="errorMessage" class="text-red-500 text-center mt-2">
                    {{ errorMessage }}
                </p>
            </transition>
            <transition name="fade">
                <p
                    v-if="notificationMessage"
                    class="text-green-500 text-center mt-2"
                >
                    {{ notificationMessage }}
                </p>
            </transition>
            <form @submit.prevent="verifyOtp">
                <div class="mt-2 flex flex-row gap-2 justify-center">
                    <input
                        v-for="(digit, index) in otpDigits"
                        :key="index"
                        :value="form.otp[index] || ''"
                        type="text"
                        class="border-2 border-black w-12 h-14 text-2xl rounded-xl text-center"
                        maxlength="1"
                        ref="otpInputs"
                        @input="handleInputChange(index, $event)"
                        @keydown.backspace="focusPreviousInput(index)"
                    />
                </div>
                <div class="flex gap-4 mt-5">
                    <button
                        type="submit"
                        class="w-1/2 bg-blue-400 text-black font-black content-center py-1 rounded-full hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out focus:outline-none text-lg mx-auto block"
                        :disabled="form.processing || !isValidOtp"
                        :class="{
                            'cursor-not-allowed':
                                form.processing || !isValidOtp,
                        }"
                    >
                        Submit
                    </button>
                    <button
                        type="button"
                        @click="cancel"
                        class="w-1/2 bg-red-400 text-black font-black content-center py-1 rounded-full hover:bg-red-900 hover:text-white transition duration-300 ease-in-out focus:outline-none text-lg mx-auto block"
                    >
                        Cancel
                    </button>
                </div>
                <!-- Resend OTP Button -->
                <div class="mt-3 text-center">
                    <button
                        :disabled="isResending || form.processing || hasResent"
                        @click.prevent="resendOtp"
                        class="text-white text-base font-bold underline hover:text-blue-700"
                        :class="{
                            'opacity-50 cursor-not-allowed':
                                isResending || form.processing || hasResent,
                        }"
                    >
                        {{ resendButtonText }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Modal for OTP sent successfully notification -->
        <transition name="fade">
            <div
                v-if="showSuccessModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            >
                <div class="bg-white p-6 rounded-lg shadow-xl text-center">
                    <div class="text-green-500 mb-4">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-16 w-16 mx-auto"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <circle cx="12" cy="12" r="10" stroke-width="2" />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4"
                            />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold mb-4">An OTP Has Been Sent</h2>
                    <p class="mb-4 font-medium">
                        Your one-time password has been <br />
                        successfully sent.
                    </p>
                    <button
                        @click="closeSuccessModal"
                        class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                    >
                        Thank You
                    </button>
                </div>
            </div>
        </transition>

        <!-- Modal for 'OTP already sent' notification -->
        <transition name="fade">
            <div
                v-if="showWarningModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            >
                <div class="bg-white p-6 rounded-lg shadow-xl text-center">
                    <div class="text-yellow-500 mb-4">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-16 w-16 mx-auto"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                            />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold mb-4">OTP Already Sent</h2>
                    <p class="mb-4 font-medium">
                        An OTP was already sent recently.<br />
                        Pleas wait before requesting a new one.
                    </p>
                    <button
                        @click="closeWarningModal"
                        class="mt-4 bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                    >
                        Close
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import axios from "axios";

// Initialize form with otp as an array
const form = useForm({
    otp: Array(6).fill(""), // Initialize otp as an array with empty strings
});

const isSending = ref(false);
const isResending = ref(false);
const errorMessage = ref("");
const notificationMessage = ref("");
const hasResent = ref(false);
const resendCountdown = ref(30);
const showSuccessModal = ref(false);
const showWarningModal = ref(false);

const otpDigits = computed(() => form.otp);

const isValidOtp = computed(() => {
    return form.otp.length === 6 && !form.otp.includes("");
});

const resendButtonText = computed(() => {
    if (hasResent.value) {
        return `Resend in ${resendCountdown.value}s`;
    }
    return "Resend";
});

// Convert OTP array to string for submission
const otpAsString = computed(() => form.otp.join(""));

const verifyOtp = () => {
    if (!isValidOtp.value) {
        setErrorMessage("Please enter a valid 6-digit OTP.");
        return;
    }

    const otpString = form.otp.join("");

    form.post(route("otp.verify"), {
        preserveState: true,
        preserveScroll: true,
        data: { otp: otpString },
        onSuccess: (response) => {
            if (response?.props?.flash?.message) {
                setNotificationMessage(response.props.flash.message);
            } else {
                setNotificationMessage(
                    "OTP verification succeeded, but no message was provided."
                );
            }
        },
        onError: (errors) => {
            setErrorMessage(errors.otp || "Invalid OTP. Please try again.");
        },
    });
};

// Helper functions to set messages and clear them after a delay
const setErrorMessage = (message) => {
    errorMessage.value = message;
    clearNotificationMessage();
    setTimeout(() => {
        errorMessage.value = "";
    }, 5000);
};

const setNotificationMessage = (message) => {
    notificationMessage.value = message;
    clearErrorMessage();
    setTimeout(() => {
        notificationMessage.value = "";
    }, 5000);
};

const clearErrorMessage = () => {
    errorMessage.value = "";
};

const clearNotificationMessage = () => {
    notificationMessage.value = "";
};

// Send OTP
const sendOtp = async () => {
    if (isSending.value || form.processing) return;

    isSending.value = true;
    errorMessage.value = "";

    try {
        const response = await axios.post(route("otp.send"));
        showSuccessModal.value = true;
    } catch (error) {
        if (error.response?.status === 429) {
            showWarningModal.value = true;
        } else {
            errorMessage.value =
                error.response?.data?.error ||
                "Failed to send OTP. Please try again.";
        }
    } finally {
        isSending.value = false;
    }
};

// Resend OTP
const resendOtp = () => {
    if (isResending.value || form.processing || hasResent.value) return;

    isResending.value = true;
    hasResent.value = true;
    errorMessage.value = "";
    notificationMessage.value = "";

    axios
        .post(route("otp.resend"))
        .then((response) => {
            if (response.data.success) {
                showSuccessModal.value = true;
                startResendCountdown();
            } else {
                showWarningModal.value = true;
            }
        })
        .catch((error) => {
            if (error.response?.status === 429) {
                showWarningModal.value = true;
            } else {
                errorMessage.value =
                    error.response?.data?.message ||
                    "Failed to resend OTP. Please try again.";
            }
        })
        .finally(() => {
            isResending.value = false;
        });
};

const startResendCountdown = () => {
    resendCountdown.value = 30;
    const countdownInterval = setInterval(() => {
        resendCountdown.value--;
        if (resendCountdown.value <= 0) {
            clearInterval(countdownInterval);
            hasResent.value = false;
        }
    }, 1000);
};

const handleInputChange = (index, event) => {
    const value = event.target.value;
    if (/^[0-9]?$/.test(value)) {
        // Only allow digits
        form.otp = form.otp.map((digit, i) => (i === index ? value : digit)); // Update OTP array
        focusNextInput(index);
    }
};

// Cancel action
const cancel = () => {
    router.visit(route("login"));
};

const focusNextInput = (index) => {
    if (form.otp[index] && index < 5) {
        // Move focus if current index has value and is not the last input
        nextTick(() => {
            const inputs = document.querySelectorAll("input");
            if (inputs[index + 1]) {
                inputs[index + 1].focus();
            }
        });
    }
};

const focusPreviousInput = (index) => {
    if (form.otp[index] === "" && index > 0) {
        // Move focus if current index is empty and not the first input
        nextTick(() => {
            const inputs = document.querySelectorAll("input");
            if (inputs[index - 1]) {
                inputs[index - 1].focus();
            }
        });
    }
};

const closeSuccessModal = () => {
    showSuccessModal.value = false;
};

const closeWarningModal = () => {
    showWarningModal.value = false;
};

onMounted(() => {
    hasResent.value = false;
    sendOtp(); // Send OTP when the component is mounted
});
</script>
