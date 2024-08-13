<template>
    <BTemp
        :pageTitle="'List of Barangays in New Bataan, Davao de Oro'"
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
    { name: "Andap", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bantacan", beneficiaries: "1,045", amount: "207,135" },
    { name: "Batinao", beneficiaries: "3,764", amount: "245,235" },
    { name: "Cabinuangan", beneficiaries: "2,983", amount: "215,064" },
    { name: "Camanlangan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Cogonon", beneficiaries: "3,513", amount: "251,123" },
    { name: "Fatima", beneficiaries: "2,124", amount: "152,724" },
    { name: "Kahayag", beneficiaries: "2,124", amount: "193,513" },
    { name: "Katipunan", beneficiaries: "1,972", amount: "212,523" },
    { name: "Magangit", beneficiaries: "1,241", amount: "183,523" },
    { name: "Magsaysay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Manurigao", beneficiaries: "2,870", amount: "251,624" },
    { name: "Pagsabangan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Panag", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Roque", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tandawan", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
