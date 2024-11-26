<template>
    <div class="mb-4 -mt-10">
        <label class="block text-baase font-medium text-gray-700">{{
            label
        }}</label>
        <div class="relative mt-1 flex gap-2">
            <select
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                class="block w-full rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-base disabled:bg-gray-100"
                :disabled="loading"
            >
                <option value="">Select {{ label }}</option>
                <option
                    v-for="item in items"
                    :key="item.id"
                    :value="item.id"
                    :selected="item.id === modelValue"
                >
                    {{ item.value }}
                </option>
            </select>
            <button
                type="button"
                @click="$emit('add')"
                :disabled="loading"
                class="inline-flex items-center justify-center py-2 px-2 border border-transparent text-3xl leading-4 font-medium rounded-full text-white bg-blue-900 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 whitespace-nowrap min-w-[2.5rem]"
            >
                <span v-if="loading">
                    <svg
                        class="animate-spin h-4 w-4 text-white"
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
                </span>
                <span v-else>+</span>
            </button>
        </div>
        <p v-if="error" class="mt-2 text-sm text-red-600" role="alert">
            {{ error }}
        </p>
        <p v-if="loading" class="mt-2 text-sm text-gray-500">Loading...</p>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [String, Number],
        required: true,
    },
    items: {
        type: Array,
        required: true,
        default: () => [],
        validator: (items) =>
            items.every(
                (item) =>
                    item &&
                    typeof item === "object" &&
                    "id" in item &&
                    "value" in item &&
                    "label" in item
            ),
    },
    loading: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: null,
    },
});
</script>

<style scoped>
/* Optional: Add smooth transitions */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Optional: Add transition for hover states */
button {
    transition: all 0.2s ease-in-out;
}

/* Optional: Improve focus visibility for accessibility */
select:focus,
button:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}
</style>
