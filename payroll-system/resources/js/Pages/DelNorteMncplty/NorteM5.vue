<template>
    <BTemp
        :pageTitle="'List of Barangays in New Corella, Davao del Norte'"
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
    { name: "Cabidianan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Carcor", beneficiaries: "1,045", amount: "207,135" },
    { name: "Del Monte", beneficiaries: "3,764", amount: "245,235" },
    { name: "Del Pilar", beneficiaries: "2,983", amount: "215,064" },
    { name: "El Salvador", beneficiaries: "1,870", amount: "211,233" },
    { name: "Limba-an", beneficiaries: "3,513", amount: "251,123" },
    { name: "Macgum", beneficiaries: "2,124", amount: "152,724" },
    { name: "Mambing", beneficiaries: "2,124", amount: "193,513" },
    { name: "Mesaoy", beneficiaries: "1,972", amount: "212,523" },
    { name: "New Bohol", beneficiaries: "1,241", amount: "183,523" },
    { name: "New Cortez", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Sambog", beneficiaries: "2,870", amount: "251,624" },
    { name: "Patrocenio", beneficiaries: "2,870", amount: "251,624" },
    { name: "Poblacion", beneficiaries: "1,045", amount: "207,135" },
    { name: "San Jose", beneficiaries: "3,764", amount: "245,235" },
    { name: "San Roque", beneficiaries: "2,983", amount: "215,064" },
    { name: "Santa Cruz", beneficiaries: "1,870", amount: "211,233" },
    { name: "Santa Fe", beneficiaries: "3,513", amount: "251,123" },
    { name: "Santo Niño", beneficiaries: "2,124", amount: "152,724" },
    { name: "Suawon", beneficiaries: "2,124", amount: "193,513" },
]);
</script>
