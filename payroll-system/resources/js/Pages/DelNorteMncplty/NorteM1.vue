<template>
    <BTemp
        :pageTitle="'List of Barangays in Asuncion, Davao del Norte'"
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
    { name: "Binancian", beneficiaries: "1,245", amount: "102,673" },
    { name: "Buan", beneficiaries: "1,045", amount: "207,135" },
    { name: "Buclad", beneficiaries: "3,764", amount: "245,235" },
    { name: "Cabaywa", beneficiaries: "2,983", amount: "215,064" },
    { name: "Camansa", beneficiaries: "1,870", amount: "211,233" },
    { name: "Cambanogoy", beneficiaries: "3,513", amount: "251,123" },
    { name: "Camoning", beneficiaries: "2,124", amount: "152,724" },
    { name: "Canatan", beneficiaries: "2,124", amount: "193,513" },
    { name: "Concepcion", beneficiaries: "1,972", amount: "212,523" },
    { name: "Doña Andrea", beneficiaries: "1,241", amount: "183,523" },
    { name: "Magatos", beneficiaries: "2,870", amount: "251,624" },
    { name: "Napungas", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Bantayan", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Loon", beneficiaries: "3,764", amount: "245,235" },
    { name: "New Santiago", beneficiaries: "2,983", amount: "215,064" },
    { name: "Pamacaun", beneficiaries: "1,870", amount: "211,233" },
    { name: "Sagayen", beneficiaries: "2,124", amount: "152,724" },
    { name: "San Vicente", beneficiaries: "2,124", amount: "193,513" },
    { name: "Santa Filomena", beneficiaries: "1,972", amount: "212,523" },
    { name: "Sonlon", beneficiaries: "1,241", amount: "183,523" },
]);
</script>
