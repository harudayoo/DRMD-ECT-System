<template>
    <BTemp
        :pageTitle="'List of Barangays in Malalag, Davao del Sur'"
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
    { name: "Bagumbayan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Baybay", beneficiaries: "1,045", amount: "207,135" },
    { name: "Bolton", beneficiaries: "3,764", amount: "245,235" },
    { name: "Bulacan", beneficiaries: "2,983", amount: "215,064" },
    { name: "Caputian", beneficiaries: "1,870", amount: "211,233" },
    { name: "Ibo", beneficiaries: "3,513", amount: "251,123" },
    { name: "Kiblagon", beneficiaries: "2,124", amount: "152,724" },
    { name: "Lapu-lapu", beneficiaries: "2,124", amount: "193,513" },
    { name: "Mabini", beneficiaries: "1,972", amount: "212,523" },
    { name: "New Baclayon", beneficiaries: "1,241", amount: "183,523" },
    { name: "Pitu", beneficiaries: "2,870", amount: "251,624" },
    { name: "Poblacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "Rizal", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Isidro", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tagansule", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
