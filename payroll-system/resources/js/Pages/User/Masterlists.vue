<template>
    <SidebarLayout>
        <div class="h-screen flex flex-col overflow-hidden">
            <!-- Main content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Header -->
                <header class="flex items-center">
                    <div class="ml-1 -mb-3 mx-auto px-4 flex items-center">
                        <button
                            @click="goBack"
                            class="m-4 -ml-1 mt-7 hover:text-gray-600 transition-colors duration-200"
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
                                    stroke-width="3"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                ></path>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-2xl font-black text-black">
                                {{ barangay.barangayName }} Beneficiaries
                            </h1>
                        </div>
                    </div>
                </header>

                <!-- Beneficiaries Table -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <div class="flex mx-4 mb-4 h-full">
                        <div
                            class="flex-1 shadow-2xl rounded-xl p-2 ml-2 flex flex-col"
                        >
                            <div class="rounded-t-xl overflow-hidden">
                                <table class="w-full table-fixed">
                                    <thead class="bg-stone-100">
                                        <tr>
                                            <th
                                                class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider text-center"
                                            >
                                                Beneficiary Number
                                            </th>
                                            <th
                                                class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider text-center"
                                            >
                                                Last Name
                                            </th>
                                            <th
                                                class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider text-center"
                                            >
                                                First Name
                                            </th>
                                            <th
                                                class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider text-center"
                                            >
                                                Middle Name
                                            </th>
                                            <th
                                                class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider text-center"
                                            >
                                                Extension Name
                                            </th>
                                            <th
                                                class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider text-center"
                                            >
                                                Date of Birth
                                            </th>
                                            <th
                                                class="px-2 py-1 text-lg font-medium text-black uppercase tracking-wider text-center"
                                            >
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div
                                class="overflow-y-auto flex-1"
                                style="max-height: calc(100vh - 220px)"
                            >
                                <table class="w-full table-fixed">
                                    <tbody
                                        class="bg-white divide-y text-xl text-center font-medium divide-gray-200"
                                    >
                                        <tr
                                            v-for="beneficiary in beneficiaries"
                                            :key="beneficiary.beneficiaryID"
                                        >
                                            <td
                                                class="px-2 py-3 whitespace-nowrap"
                                            >
                                                {{
                                                    beneficiary.beneficiaryNumber
                                                }}
                                            </td>
                                            <td
                                                class="px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ beneficiary.lastName }}
                                            </td>
                                            <td
                                                class="px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ beneficiary.firstName }}
                                            </td>
                                            <td
                                                class="px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ beneficiary.middleName }}
                                            </td>
                                            <td
                                                class="px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ beneficiary.extensionName }}
                                            </td>
                                            <td
                                                class="px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ beneficiary.dateOfBirth }}
                                            </td>
                                            <td
                                                class="px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ beneficiary.status }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SidebarLayout>
</template>

<script setup>
import { ref } from "vue";
import SidebarLayout from "../../Layouts/PrimitiveSB.vue";

const props = defineProps({
    barangay: Object,
    beneficiaries: Array,
});

const goBack = () => {
    console.log("Go back clicked");
    window.history.back();
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");

.font-inter {
    font-family: "Inter", sans-serif;
}

.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #000000;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #000000;
}
</style>
