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
                    <div class="col-span-2">
                        <label for="rcdID" class="block mb-2 font-medium"
                            >RCD</label
                        >
                        <div class="relative">
                            <input
                                type="text"
                                v-model="rcdSearch"
                                placeholder="Search RCDs..."
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                                @input="debounceSearch"
                                :disabled="form.processing"
                            />
                            <div
                                v-if="isLoadingRcds"
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
                            id="rcdID"
                            v-model="form.rcdID"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                            :disabled="form.processing"
                            @change="validateRcdID"
                        >
                            <option value="" disabled>Select RCD</option>
                            <option
                                v-for="rcd in filteredRcds"
                                :key="rcd.rcdID"
                                :value="rcd.rcdID"
                            >
                                {{ rcd.rcdID }} - {{ rcd.rcdName }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.rcdID || validationErrors.rcdID"
                            class="text-sm text-red-500 mt-1"
                        >
                            {{ form.errors.rcdID || validationErrors.rcdID }}
                        </div>
                    </div>
                </div>
                <div
                    v-if="form.errors.generalError"
                    class="mt-4 text-sm text-red-500 p-3 bg-red-50 rounded-md"
                >
                    {{ form.errors.generalError }}
                </div>
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
    rcds: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["close", "cdr-created"]);

const form = useForm({
    cdrName: "",
    rcdID: "",
});

const validationErrors = ref({
    cdrName: "",
    rcdID: "",
});

const isFormValid = computed(() => {
    return (
        form.cdrName.length > 0 &&
        form.rcdID.length > 0 &&
        Object.values(validationErrors.value).every((error) => !error)
    );
});

const rcdSearch = ref("");
const isLoadingRcds = ref(false);
const filteredRcds = ref(props.rcds);

const searchRcds = async (search) => {
    if (!search) {
        filteredRcds.value = props.rcds;
        return;
    }

    try {
        isLoadingRcds.value = true;
        const response = await axios.get(route("cdr.searchRcds", { search }));
        filteredRcds.value = response.data;
    } catch (error) {
        console.error("Error searching RCDs:", error);
        form.setError(
            "generalError",
            "Failed to search RCDs. Please try again."
        );
    } finally {
        isLoadingRcds.value = false;
    }
};

const debounceSearch = debounce(() => {
    searchRcds(rcdSearch.value);
}, 300);

const validateCdrName = () => {
    validationErrors.value.cdrName = "";
    if (!form.cdrName) {
        validationErrors.value.cdrName = "CDR Name is required";
    } else if (form.cdrName.length < 3) {
        validationErrors.value.cdrName =
            "CDR Name must be at least 3 characters";
    } else if (form.cdrName.length > 35) {
        validationErrors.value.cdrName =
            "CDR Name must not exceed 35 characters";
    }
};

const validateRcdID = () => {
    validationErrors.value.rcdID = "";
    if (!form.rcdID) {
        validationErrors.value.rcdID = "Please select an RCD";
    }
};

const validateAndSubmit = () => {
    validateCdrName();
    validateRcdID();

    if (!form.cdrName || !form.rcdID) {
        return;
    }

    submitCdr();
};

const submitCdr = () => {
    form.post(route("cdr.store"), {
        onSuccess: () => {
            emit("cdr-created");
            closeModal();
        },
        onError: (errors) => {
            console.error("Form submission errors:", errors);
            if (errors.generalError) {
                form.setError("generalError", errors.generalError);
            }
        },
    });
};

const resetForm = () => {
    form.reset();
    validationErrors.value = {
        cdrName: "",
        rcdID: "",
    };
    rcdSearch.value = "";
    filteredRcds.value = props.rcds;
};

const closeModal = () => {
    emit("close");
    resetForm();
};

onMounted(() => {
    resetForm();
});

watch(
    () => props.rcds,
    (newRcds) => {
        if (!rcdSearch.value) {
            filteredRcds.value = newRcds;
        }
    }
);
</script>
