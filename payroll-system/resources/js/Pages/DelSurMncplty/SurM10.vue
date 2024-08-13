<template>
    <BTemp
        :pageTitle="'List of Barangays in Santa Cruz, Davao del Sur'"
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
    { name: "Astorga", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bato", beneficiaries: "1,245", amount: "102,673" },
    { name: "Coronon", beneficiaries: "1,045", amount: "207,135" },
    { name: "Darong", beneficiaries: "2,983", amount: "215,064" },
    { name: "Inawayan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Jose Rizal", beneficiaries: "3,513", amount: "251,123" },
    { name: "Matutungan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Melilia", beneficiaries: "2,124", amount: "193,513" },
    { name: "Saliducon", beneficiaries: "1,972", amount: "212,523" },
    { name: "Sibulan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Sinoron", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tagabuli", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tibolo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tuban", beneficiaries: "1,972", amount: "212,523" },
    { name: "Zone I", beneficiaries: "1,241", amount: "183,523" },
    { name: "Zone II", beneficiaries: "2,870", amount: "251,624" },
    { name: "Zone III", beneficiaries: "2,870", amount: "251,624" },
    { name: "Zone IV", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
