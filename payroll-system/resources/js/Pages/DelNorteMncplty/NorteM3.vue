<template>
    <BTemp
        :pageTitle="'List of Barangays in Carmen, Davao del Norte'"
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
    { name: "Alejal", beneficiaries: "1,245", amount: "102,673" },
    { name: "Anibongan", beneficiaries: "1,045", amount: "207,135" },
    { name: "Asuncion", beneficiaries: "3,764", amount: "245,235" },
    { name: "Cebulano", beneficiaries: "2,983", amount: "215,064" },
    { name: "Guadalupe", beneficiaries: "1,870", amount: "211,233" },
    { name: "Ising", beneficiaries: "3,513", amount: "251,123" },
    { name: "La Paz", beneficiaries: "2,124", amount: "152,724" },
    { name: "Mabaus", beneficiaries: "2,124", amount: "193,513" },
    { name: "Mabuhay", beneficiaries: "1,972", amount: "212,523" },
    { name: "Magsaysay", beneficiaries: "1,241", amount: "183,523" },
    { name: "Mangalcal", beneficiaries: "2,870", amount: "251,624" },
    { name: "Minda", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Camiling", beneficiaries: "2,870", amount: "251,624" },
    { name: "Salvacion", beneficiaries: "1,045", amount: "207,135" },
    { name: "San Isidro", beneficiaries: "3,764", amount: "245,235" },
    { name: "Santo Niño", beneficiaries: "1,870", amount: "211,233" },
    { name: "Taba", beneficiaries: "3,513", amount: "251,123" },
    { name: "Tibulao", beneficiaries: "2,124", amount: "152,724" },
    { name: "Tubod", beneficiaries: "2,124", amount: "193,513" },
    { name: "Tuganay", beneficiaries: "1,972", amount: "212,523" },
]);
</script>
