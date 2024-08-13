<template>
    <BTemp
        :pageTitle="'List of Barangays in Mawab, Davao de Oro'"
        :statistics="statisticsData"
        :tableHeaders="['Barangay', 'Number of Beneficiary', 'Amount']"
        :tableData="barangayData"
    />
</template>

<script setup lang="ts">
import BTemp from "@/Layouts/BTemp.vue";
import { ref } from "vue";

const statisticsData = ref([
    { label: "Claimed", value: "1,523" },
    { label: "Unclaimed", value: "789" },
    { label: "Disqualified", value: "1,219" },
    { label: "Duplicates", value: "121" },
]);

const barangayData = ref([
    { name: "Andili", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bawani", beneficiaries: "1,045", amount: "207,135" },
    { name: "Concepcion", beneficiaries: "3,764", amount: "245,235" },
    { name: "Malinawon", beneficiaries: "2,983", amount: "215,064" },
    { name: "Nueva Visayas", beneficiaries: "1,870", amount: "211,233" },
    { name: "Neavo Iloco", beneficiaries: "3,513", amount: "251,123" },
    { name: "Poblacion", beneficiaries: "2,124", amount: "152,724" },
    { name: "Salvacion", beneficiaries: "2,124", amount: "193,513" },
    { name: "Saosao", beneficiaries: "1,241", amount: "183,523" },
    { name: "Sawangan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tuboran", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
