<template>
    <BTemp
        :pageTitle="'List of Barangays in San Isidro, Davao Oriental'"
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
    { name: "Baon", beneficiaries: "1,245", amount: "102,673" },
    { name: "Batobato", beneficiaries: "1,045", amount: "207,135" },
    { name: "Bitaogan", beneficiaries: "3,764", amount: "245,235" },
    { name: "Cambaleon", beneficiaries: "1,870", amount: "211,233" },
    { name: "Dugmanon", beneficiaries: "3,513", amount: "251,123" },
    { name: "Iba", beneficiaries: "2,124", amount: "152,724" },
    { name: "La Union", beneficiaries: "2,124", amount: "193,513" },
    { name: "Lapu-lapu", beneficiaries: "1,972", amount: "212,523" },
    { name: "Maag", beneficiaries: "1,241", amount: "183,523" },
    { name: "Manikling", beneficiaries: "2,870", amount: "251,624" },
    { name: "Maputi", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Miguel", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Roque", beneficiaries: "2,870", amount: "251,624" },
    { name: "Santo Rosario", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sudlon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Talisay", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
