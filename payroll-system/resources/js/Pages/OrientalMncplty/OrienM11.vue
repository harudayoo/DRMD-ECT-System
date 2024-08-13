<template>
    <BTemp
        :pageTitle="'List of Barangays in Tarragona, Davao Oriental'"
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
    { name: "Cabagayan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Central", beneficiaries: "1,045", amount: "207,135" },
    { name: "Dadong", beneficiaries: "3,764", amount: "245,235" },
    { name: "Jovellar", beneficiaries: "1,870", amount: "211,233" },
    { name: "Limot", beneficiaries: "3,513", amount: "251,123" },
    { name: "Lucatan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Maganda", beneficiaries: "2,124", amount: "193,513" },
    { name: "Ompao", beneficiaries: "1,972", amount: "212,523" },
    { name: "Tomoaong", beneficiaries: "1,241", amount: "183,523" },
    { name: "Tubaon", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
