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
                src="/icons/logo.png"
                alt="form-bg"
                class="mx-auto w-full -mt-2"
            />
            <p class="text-center mt-2 text-base">
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
                <div class="flex gap-4 mt-3">
                    <button
                        type="submit"
                        class="w-1/2 bg-blue-400 text-black font-black content-center py-1 rounded-full hover:bg-blue-900 hover:text-white transition duration-300 ease-in-out focus:outline-none text-lg mx-auto block"
                        :disabled="form.processing || !isValidOtp"
                        :class="{
                            'opacity-50 cursor-not-allowed':
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
                    <div class="flex gap-4 mt-3"></div>
                    <!-- Resend OTP Button -->
                    <button
                        :disabled="isResending || form.processing || hasResent"
                        @click.prevent="resendOtp"
                        class="text-white text-base font-bold mt-2 block text-center underline hover:text-blue-700"
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

    const otpString = form.otp.join(""); // Convert the OTP array to a string

    form.post(route("otp.verify"), {
        preserveState: true,
        preserveScroll: true,
        data: { otp: otpString }, // Send OTP as a string
        onSuccess: (response) => {
            if (response?.props?.flash?.message) {
                setNotificationMessage(response.props.flash.message);
            } else {
                setNotificationMessage(
                    "OTP verification succeeded, but no message was provided."
                );
            }
            setTimeout(() => {
                router.visit(route("user.dashboard"));
            }, 1500);
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
        notificationMessage.value = response.data.message;
    } catch (error) {
        errorMessage.value =
            error.response?.data?.error ||
            "Failed to send OTP. Please try again.";
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
                notificationMessage.value = response.data.message;
                startResendCountdown();
            } else {
                errorMessage.value = response.data.message;
            }
        })
        .catch((error) => {
            errorMessage.value =
                error.response?.data?.message ||
                "Failed to resend OTP. Please try again.";
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

onMounted(() => {
    hasResent.value = false;
    sendOtp(); // Send OTP when the component is mounted
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
