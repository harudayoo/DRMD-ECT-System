<template>
    <BTemp
        :pageTitle="'List of Barangays in Sulop, Davao del Sur'"
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
    { name: "Balasinon", beneficiaries: "1,245", amount: "102,673" },
    { name: "Buguis", beneficiaries: "1,045", amount: "207,135" },
    { name: "Carre", beneficiaries: "3,764", amount: "245,235" },
    { name: "Clib", beneficiaries: "2,983", amount: "215,064" },
    { name: "Harada Butai", beneficiaries: "1,870", amount: "211,233" },
    { name: "Katipunan", beneficiaries: "3,513", amount: "251,123" },
    { name: "Kiblagon", beneficiaries: "2,124", amount: "152,724" },
    { name: "Labon", beneficiaries: "2,124", amount: "193,513" },
    { name: "Laperas", beneficiaries: "1,972", amount: "212,523" },
    { name: "Lapla", beneficiaries: "1,241", amount: "183,523" },
    { name: "Litos", beneficiaries: "2,870", amount: "251,624" },
    { name: "Luparan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mckinley", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Cebu", beneficiaries: "1,045", amount: "207,135" },
    { name: "Osmeña", beneficiaries: "3,764", amount: "245,235" },
    { name: "Palili", beneficiaries: "2,983", amount: "215,064" },
    { name: "Parame", beneficiaries: "1,870", amount: "211,233" },
    { name: "Poblacion", beneficiaries: "3,513", amount: "251,123" },
    { name: "Roxas", beneficiaries: "2,124", amount: "152,724" },
    { name: "Solongvale", beneficiaries: "2,124", amount: "193,513" },
    { name: "Tagolilong", beneficiaries: "1,972", amount: "212,523" },
    { name: "Tala-o", beneficiaries: "1,241", amount: "183,523" },
    { name: "Talas", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tanwalang", beneficiaries: "2,870", amount: "251,624" },
    { name: "Waterfall", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
