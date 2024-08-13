<template>
    <BTemp
        :pageTitle="'List of Barangays in Caraga, Davao Oriental'"
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
    { name: "Alvar", beneficiaries: "1,245", amount: "102,673" },
    { name: "Caningag", beneficiaries: "1,045", amount: "207,135" },
    { name: "Don Leon Balante", beneficiaries: "3,764", amount: "245,235" },
    { name: "Lamiawan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Manorigao", beneficiaries: "3,513", amount: "251,123" },
    { name: "Mercedes", beneficiaries: "2,124", amount: "152,724" },
    { name: "Palma Gil", beneficiaries: "2,124", amount: "193,513" },
    { name: "Pichon", beneficiaries: "1,972", amount: "212,523" },
    { name: "Poblacion", beneficiaries: "1,241", amount: "183,523" },
    { name: "San Antonio", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Jose", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Luis", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Miguel", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Pedro", beneficiaries: "2,870", amount: "251,624" },
    { name: "Santa Fe", beneficiaries: "2,870", amount: "251,624" },
    { name: "Santiago", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sobrecarey", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
