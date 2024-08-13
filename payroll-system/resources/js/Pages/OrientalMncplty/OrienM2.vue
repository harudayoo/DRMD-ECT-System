<template>
    <BTemp
        :pageTitle="'List of Barangays in Banaybanay, Davao Oriental'"
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
    { name: "Cabangcalan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Caganganan", beneficiaries: "1,045", amount: "207,135" },
    { name: "Calubihan", beneficiaries: "3,764", amount: "245,235" },
    { name: "Causwagan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Mahayag", beneficiaries: "3,513", amount: "251,123" },
    { name: "Maputi", beneficiaries: "2,124", amount: "152,724" },
    { name: "Mogbongcogon", beneficiaries: "2,124", amount: "193,513" },
    { name: "Panikian", beneficiaries: "1,972", amount: "212,523" },
    { name: "Pintatagan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Piso Proper", beneficiaries: "2,870", amount: "251,624" },
    { name: "Poblacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "Punta Linao", beneficiaries: "2,870", amount: "251,624" },
    { name: "Rang-ay", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Vicente", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
