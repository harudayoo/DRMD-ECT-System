<!-- NewCDRModal.vue -->
<template>
    <div
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50"
        @click.self="closeModal"
    >
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-2xl">
            <h2 class="text-2xl font-bold mb-4 text-center">
                Generate New CDR
            </h2>
            <form @submit.prevent="validateAndSubmit">
                <div class="grid grid-cols-2 gap-4">
                    <!-- CDR Name Input -->
                    <div class="col-span-2">
                        <label for="cdrName" class="block mb-2 font-medium"
                            >CDR Name</label
                        >
                        <input
                            type="text"
                            id="cdrName"
                            v-model="form.cdrName"
                            maxlength="35"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                            :disabled="form.processing"
                            @blur="validateCdrName"
                        />
                        <div
                            v-if="
                                form.errors.cdrName || validationErrors.cdrName
                            "
                            class="text-sm text-red-500 mt-1"
                        >
                            {{
                                form.errors.cdrName || validationErrors.cdrName
                            }}
                        </div>
                    </div>

                    <!-- Payroll Selection -->
                    <div class="col-span-2">
                        <label for="payrollID" class="block mb-2 font-medium"
                            >Payroll</label
                        >
                        <div class="relative">
                            <input
                                type="text"
                                v-model="payrollSearch"
                                placeholder="Search payrolls by number or name..."
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                                @input="debouncedSearch"
                                :disabled="form.processing"
                            />
                            <div
                                v-if="isLoadingPayrolls"
                                class="absolute right-3 top-2"
                            >
                                <svg
                                    class="animate-spin h-5 w-5 text-blue-500"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <select
                            id="payrollID"
                            v-model="form.payrollID"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                            :disabled="form.processing"
                            @change="validatePayrollID"
                        >
                            <option value="" disabled>Select Payroll</option>
                            <option
                                v-for="payroll in payrolls"
                                :key="payroll.payrollID"
                                :value="payroll.payrollID"
                            >
                                {{ payroll.payrollNumber }} -
                                {{ payroll.payrollName }}
                            </option>
                        </select>
                        <div
                            v-if="
                                form.errors.payrollID ||
                                validationErrors.payrollID
                            "
                            class="text-sm text-red-500 mt-1"
                        >
                            {{
                                form.errors.payrollID ||
                                validationErrors.payrollID
                            }}
                        </div>
                    </div>
                </div>

                <!-- Error Message -->
                <div
                    v-if="form.errors.generalError"
                    class="mt-4 text-sm text-red-500 p-3 bg-red-50 rounded-md"
                >
                    {{ form.errors.generalError }}
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-4">
                    <button
                        type="button"
                        @click="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-full hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        :disabled="form.processing"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-900 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center"
                        :disabled="form.processing"
                    >
                        <svg
                            v-if="form.processing"
                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        {{ form.processing ? "Creating..." : "Create CDR" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import axios from "axios";

const props = defineProps({
    initialPayrolls: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["close", "cdr-created"]);

// Form initialization
const form = useForm({
    cdrName: "",
    payrollID: "",
});

// State management
const validationErrors = ref({
    cdrName: "",
    payrollID: "",
});

const payrollSearch = ref("");
const isLoadingPayrolls = ref(false);
const filteredPayrolls = ref(props.initialPayrolls);
const payrolls = ref(props.initialPayrolls);

// Computed properties
const isFormValid = computed(() => {
    return (
        form.cdrName.length > 0 &&
        form.payrollID.length > 0 &&
        Object.values(validationErrors.value).every((error) => !error)
    );
});

// Methods
const searchPayrolls = async (search) => {
    if (!search) {
        filteredPayrolls.value = props.initialPayrolls;
        return;
    }

    try {
        isLoadingPayrolls.value = true;
        const response = await axios.get(
            route("cdr.searchPayrolls", { search })
        );
        filteredPayrolls.value = response.data;
    } catch (error) {
        console.error("Error searching payrolls:", error);
        form.setError(
            "generalError",
            "Failed to search payrolls. Please try again."
        );
    } finally {
        isLoadingPayrolls.value = false;
    }
};

const fetchPayrolls = async () => {
    try {
        isLoadingPayrolls.value = true;
        const response = await axios.get(route("cdr.searchPayrolls"));
        payrolls.value = response.data;
    } catch (error) {
        console.error("Error fetching payrolls:", error);
        form.setError(
            "generalError",
            "Failed to fetch payrolls. Please try again."
        );
    } finally {
        isLoadingPayrolls.value = false;
    }
};

const debouncedSearch = debounce(() => {
    searchPayrolls(payrollSearch.value);
}, 300);

const validatePayrollID = () => {
    validationErrors.value.payrollID = "";
    if (!form.payrollID) {
        validationErrors.value.payrollID = "Please select a Payroll";
    }
};

const validateCdrName = () => {
    if (!form.cdrName) {
        form.setError("cdrName", "CDR Name is required");
    } else if (form.cdrName.length < 3) {
        form.setError("cdrName", "CDR Name must be at least 3 characters");
    } else if (form.cdrName.length > 35) {
        form.setError("cdrName", "CDR Name must not exceed 35 characters");
    } else {
        form.clearErrors("cdrName");
    }
};

const validateAndSubmit = () => {
    validateCdrName();

    if (!form.payrollID) {
        form.setError("payrollID", "Please select a Payroll");
        return;
    }

    if (Object.keys(form.errors).length > 0) {
        return;
    }

    form.post(route("cdr.store"), {
        onSuccess: () => {
            emit("cdr-created");
            closeModal();
        },
        onError: (errors) => {
            console.error("Form submission errors:", errors);
        },
    });
};

const resetForm = () => {
    form.reset();
    validationErrors.value = {
        cdrName: "",
        payrollID: "",
    };
    payrollSearch.value = "";
    filteredPayrolls.value = props.initialPayrolls;
};

const closeModal = () => {
    emit("close");
    resetForm();
};

// Lifecycle hooks
onMounted(() => {
    resetForm();
    fetchPayrolls();
});

watch(
    () => props.initialPayrolls,
    (newPayrolls) => {
        if (!payrollSearch.value) {
            filteredPayrolls.value = newPayrolls;
        }
    }
);
</script>
