<template>
    <div :class="{ 'w-[100%]': showGraph, 'w-full': !showGraph }">
        <div class="bg-white p-6 rounded-2xl shadow-md">
            <h4 class="text-xl font-semibold mb-2">Allocation Overview</h4>
            <div class="overflow-y-auto" :style="{ maxHeight: tableMaxHeight }">
                <table class="w-full">
                    <thead class="sticky top-0 bg-white">
                        <tr>
                            <th
                                v-for="(header, index) in tableHeaders"
                                :key="index"
                                class="text-md font-medium text-black tracking-wider"
                                :class="{
                                    'w-1/4 text-left': index === 0,
                                    'w-1/4 text-center': index === 1,
                                    'w-1/4 text-right': index === 2,
                                }"
                            >
                                {{ header }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="row in tableData"
                            :key="row.municipality"
                            class="border-t border-gray-200 cursor-pointer hover:bg-gray-100"
                            @click="navigateToMunicipality(row.municipality)"
                        >
                            <td class="py-2">
                                <div class="flex items-center justify-between">
                                    <span>{{ row.municipality }}</span>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-36 text-gray-400 flex-shrink-0"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </td>
                            <td class="py-2 text-center">
                                {{ row.beneficiaries }}
                            </td>
                            <td class="py-2 text-right">{{ row.amount }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-full flex justify-end pt-1 border-t -mb-5">
                <div class="w-1/4 flex justify-between items-center px-2 py-1">
                    <span class="text-lg font-black">Total</span>
                    <span class="text-md font-bold">{{ totalAmount }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    showGraph: boolean;
    tableHeaders: string[];
    tableData: {
        municipality: string;
        beneficiaries: string;
        amount: string;
    }[];
    tableMaxHeight: string;
}>();

const totalAmount = computed(() => {
    const total = props.tableData.reduce((sum, row) => {
        const amount = parseFloat(
            row.amount.replace("₱", "").replace(/,/g, "")
        );
        return sum + amount;
    }, 0);
    return `₱ ${total.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })}`;
});

const navigateToMunicipality = (municipality: string) => {
    const formattedMunicipality = municipality.toLowerCase().replace(/ /g, "-");
    // You might want to use Vue Router here instead of directly manipulating the URL
    window.location.href = `/municipality/${formattedMunicipality}`;
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");

body {
    font-family: "Inter", sans-serif;
}

/* Custom scroll bar for the table */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-thumb {
    background-color: #6b7280; /* Custom thumb color */
    border-radius: 10px; /* Rounded corners */
    border: 2px solid #e5e7eb; /* Border around the thumb */
}

::-webkit-scrollbar-track {
    background-color: #f3f4f6; /* Track color */
    border-radius: 10px; /* Rounded corners */
}

/* Scrollbar hover effect */
::-webkit-scrollbar-thumb:hover {
    background-color: #374151; /* Darker on hover */
}
</style>
