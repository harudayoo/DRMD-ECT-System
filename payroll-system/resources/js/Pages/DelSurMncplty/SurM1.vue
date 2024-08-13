<template>
    <BTemp
        :pageTitle="'List of Barangays in Bansalan, Davao del Sur'"
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
    { name: "Alegre", beneficiaries: "1,245", amount: "102,673" },
    { name: "Alta Vista", beneficiaries: "1,045", amount: "207,135" },
    { name: "Anonang", beneficiaries: "3,764", amount: "245,235" },
    { name: "Bitaug", beneficiaries: "2,983", amount: "215,064" },
    { name: "Bonifacio", beneficiaries: "1,870", amount: "211,233" },
    { name: "Buenavista", beneficiaries: "3,513", amount: "251,123" },
    { name: "Darapuay", beneficiaries: "2,124", amount: "152,724" },
    { name: "Dolo", beneficiaries: "2,124", amount: "193,513" },
    { name: "Eman", beneficiaries: "1,972", amount: "212,523" },
    { name: "Kinuskusan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Libertad", beneficiaries: "2,870", amount: "251,624" },
    { name: "Linawan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mabuhay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mabunga", beneficiaries: "2,983", amount: "215,064" },
    { name: "Managa", beneficiaries: "1,870", amount: "211,233" },
    { name: "Marber", beneficiaries: "3,513", amount: "251,123" },
    { name: "New Clarin", beneficiaries: "2,124", amount: "152,724" },
    { name: "Poblacion", beneficiaries: "2,124", amount: "193,513" },
    { name: "Poblacion Dos", beneficiaries: "1,972", amount: "212,523" },
    { name: "Rizal", beneficiaries: "1,241", amount: "183,523" },
    { name: "Santo Niño", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sibayan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tinongtongan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tubod", beneficiaries: "2,870", amount: "251,624" },
    { name: "Union", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
