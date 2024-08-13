<template>
    <BTemp
        :pageTitle="'List of Barangays in Padada, Davao del Sur'"
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
    { name: "Almendras", beneficiaries: "1,245", amount: "102,673" },
    {
        name: "Don Sergio Osmeña, Sr.",
        beneficiaries: "1,045",
        amount: "207,135",
    },
    { name: "Harada Butai", beneficiaries: "3,764", amount: "245,235" },
    { name: "Lower Katipunan", beneficiaries: "2,983", amount: "215,064" },
    { name: "Lower Limonzo", beneficiaries: "1,870", amount: "211,233" },
    { name: "Lower Malinao", beneficiaries: "3,513", amount: "251,123" },
    {
        name: "N C Ordaneza District",
        beneficiaries: "2,124",
        amount: "152,724",
    },
    { name: "Northern Paligue", beneficiaries: "2,124", amount: "193,513" },
    { name: "Palili", beneficiaries: "1,972", amount: "212,523" },
    { name: "Piape", beneficiaries: "1,241", amount: "183,523" },
    { name: "Punta Piape", beneficiaries: "2,870", amount: "251,624" },
    { name: "Quirino District", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Isidro", beneficiaries: "2,870", amount: "251,624" },
    { name: "Southern Paligue", beneficiaries: "1,241", amount: "183,523" },
    { name: "Tulogan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Upper Limonzo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Upper Malinao", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
