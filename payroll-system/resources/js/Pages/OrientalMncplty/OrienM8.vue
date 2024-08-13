<template>
    <BTemp
        :pageTitle="'List of Barangays in Manay, Davao Oriental'"
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
    { name: "Capasnan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Cayawan", beneficiaries: "1,045", amount: "207,135" },
    { name: "Central", beneficiaries: "3,764", amount: "245,235" },
    { name: "Concepcion", beneficiaries: "1,870", amount: "211,233" },
    { name: "Del Pilar", beneficiaries: "3,513", amount: "251,123" },
    { name: "Guza", beneficiaries: "2,124", amount: "152,724" },
    { name: "Holy Cross", beneficiaries: "2,124", amount: "193,513" },
    { name: "Lambog", beneficiaries: "1,972", amount: "212,523" },
    { name: "Mabini", beneficiaries: "1,241", amount: "183,523" },
    { name: "Manreza", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Taokanga", beneficiaries: "2,870", amount: "251,624" },
    { name: "Old Macopa", beneficiaries: "2,870", amount: "251,624" },
    { name: "Rizal", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Fermin", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Ignacio", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Isidro", beneficiaries: "2,870", amount: "251,624" },
    { name: "Zaragosa", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
