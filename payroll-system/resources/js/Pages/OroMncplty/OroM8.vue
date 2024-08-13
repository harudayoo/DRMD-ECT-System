<template>
    <BTemp
        :pageTitle="'List of Barangays in Montevista, Davao de Oro'"
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
    { name: "Banagbanag", beneficiaries: "1,245", amount: "102,673" },
    { name: "Banglasan", beneficiaries: "1,045", amount: "207,135" },
    { name: "Bangkerohan Norte", beneficiaries: "3,764", amount: "245,235" },
    { name: "Bangkerohan Sur", beneficiaries: "2,983", amount: "215,064" },
    { name: "Camansi", beneficiaries: "1,870", amount: "211,233" },
    { name: "Camantangan", beneficiaries: "3,513", amount: "251,123" },
    { name: "Canidkid", beneficiaries: "2,124", amount: "152,724" },
    { name: "Concepcion", beneficiaries: "2,124", amount: "193,513" },
    { name: "Dauman", beneficiaries: "1,972", amount: "212,523" },
    { name: "Lebanon", beneficiaries: "1,241", amount: "183,523" },
    { name: "Linoan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mayaon", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Calape", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Cebulan", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Dalaguete", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Visayas", beneficiaries: "2,870", amount: "251,624" },
    { name: "Prosperidad", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Jose", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Vicente", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tapia", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
