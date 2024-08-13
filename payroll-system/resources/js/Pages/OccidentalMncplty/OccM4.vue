<template>
    <BTemp
        :pageTitle="'List of Barangays in Santa Maria, Davao Occidental'"
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
    { name: "Basiawan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Buca", beneficiaries: "1,045", amount: "207,135" },
    { name: "Cadaatan", beneficiaries: "3,764", amount: "245,235" },
    { name: "Datu Daligasao", beneficiaries: "2,983", amount: "215,064" },
    { name: "Datu Intan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Kidadan", beneficiaries: "3,513", amount: "251,123" },
    { name: "Kinilidan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Kisulad", beneficiaries: "2,124", amount: "193,513" },
    { name: "Malalag Tubig", beneficiaries: "1,972", amount: "212,523" },
    { name: "Mamacao", beneficiaries: "1,241", amount: "183,523" },
    { name: "Ogpao", beneficiaries: "2,870", amount: "251,624" },
    { name: "Poblacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "Pongpong", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Agustin", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Antonio", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Isidro", beneficiaries: "1,972", amount: "212,523" },
    { name: "San Juan", beneficiaries: "1,241", amount: "183,523" },
    { name: "San Pedro", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Roque", beneficiaries: "2,870", amount: "251,624" },
    { name: "Santo Niño", beneficiaries: "2,870", amount: "251,624" },
    { name: "Santo Rosario", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tanglad", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
