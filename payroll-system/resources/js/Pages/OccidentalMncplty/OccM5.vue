<template>
    <BTemp
        :pageTitle="'List of Barangays in Sarangani, Davao Occidental'"
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
    { name: "Batuganding", beneficiaries: "1,245", amount: "102,673" },
    { name: "Camahual", beneficiaries: "1,045", amount: "207,135" },
    { name: "Camalig", beneficiaries: "3,764", amount: "245,235" },
    { name: "Gomtago", beneficiaries: "2,983", amount: "215,064" },
    { name: "Konel", beneficiaries: "1,870", amount: "211,233" },
    { name: "Laker", beneficiaries: "3,513", amount: "251,123" },
    { name: "Lipol", beneficiaries: "2,124", amount: "152,724" },
    { name: "Mabila", beneficiaries: "2,124", amount: "193,513" },
    { name: "Patuco", beneficiaries: "1,972", amount: "212,523" },
    { name: "Tagen", beneficiaries: "1,241", amount: "183,523" },
    { name: "Tinina", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tucal", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
