<template>
    <BTemp
        :pageTitle="'List of Barangays in Santo Tomas, Davao del Norte'"
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
    { name: "Balagunan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bobongon", beneficiaries: "1,045", amount: "207,135" },
    { name: "Casig-ang", beneficiaries: "3,764", amount: "245,235" },
    { name: "Esperanza", beneficiaries: "2,983", amount: "215,064" },
    { name: "Kimamon", beneficiaries: "1,870", amount: "211,233" },
    { name: "Kinamayan", beneficiaries: "3,513", amount: "251,123" },
    { name: "La Libertad", beneficiaries: "2,124", amount: "152,724" },
    { name: "Lungaog", beneficiaries: "2,124", amount: "193,513" },
    { name: "Magwawa", beneficiaries: "1,972", amount: "212,523" },
    { name: "New Katipunan", beneficiaries: "1,241", amount: "183,523" },
    { name: "New Visayas", beneficiaries: "2,870", amount: "251,624" },
    { name: "Pantaron", beneficiaries: "2,870", amount: "251,624" },
    { name: "Salvacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Jose", beneficiaries: "1,045", amount: "207,135" },
    { name: "San Miguel", beneficiaries: "3,764", amount: "245,235" },
    { name: "San Vicente", beneficiaries: "2,983", amount: "215,064" },
    { name: "Talomo", beneficiaries: "1,870", amount: "211,233" },
    { name: "Tibal-og", beneficiaries: "2,124", amount: "152,724" },
    { name: "Tulalian", beneficiaries: "2,124", amount: "193,513" },
]);
</script>
