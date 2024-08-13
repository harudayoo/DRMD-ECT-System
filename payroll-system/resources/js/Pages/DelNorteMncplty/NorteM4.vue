<template>
    <BTemp
        :pageTitle="'List of Barangays in Kapalong, Davao del Norte'"
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
    { name: "Capungagan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Florida", beneficiaries: "1,045", amount: "207,135" },
    { name: "Gabuyan", beneficiaries: "3,764", amount: "245,235" },
    { name: "Gupitan", beneficiaries: "2,983", amount: "215,064" },
    { name: "Katipunan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Luna", beneficiaries: "3,513", amount: "251,123" },
    { name: "Mabantao", beneficiaries: "2,124", amount: "152,724" },
    { name: "Mamacao", beneficiaries: "2,124", amount: "193,513" },
    { name: "Maniki", beneficiaries: "1,972", amount: "212,523" },
    { name: "Pag-asa", beneficiaries: "1,241", amount: "183,523" },
    { name: "Sampao", beneficiaries: "2,870", amount: "251,624" },
    { name: "Semong", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sua-on", beneficiaries: "1,045", amount: "207,135" },
    { name: "Tiburcia", beneficiaries: "1,045", amount: "207,135" },
]);
</script>
