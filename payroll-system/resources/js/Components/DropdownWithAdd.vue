<template>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">{{
            label
        }}</label>
        <div class="relative mt-1 flex gap-2">
            <select
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                class="block w-full rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:bg-gray-100"
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
                class="inline-flex items-center py-3 px-4 border border-transparent text-sm leading-4 font-medium rounded-full text-white bg-blue-900 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
            >
                <span v-if="loading">...</span>
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
defineProps({
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

defineEmits(["update:modelValue", "add"]);
</script>
