<template>
    <BTemp
        :pageTitle="'List of Barangays in Maragusan, Davao de Oro'"
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
    { name: "Bagong Silang", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bahi", beneficiaries: "1,045", amount: "207,135" },
    { name: "Cambagang", beneficiaries: "3,764", amount: "245,235" },
    { name: "Coronobe", beneficiaries: "2,983", amount: "215,064" },
    { name: "Katipunan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Lahi", beneficiaries: "3,513", amount: "251,123" },
    { name: "Langgawisan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Mabugnao", beneficiaries: "2,124", amount: "193,513" },
    { name: "Magcagong", beneficiaries: "1,972", amount: "212,523" },
    { name: "Mahayahay", beneficiaries: "1,241", amount: "183,523" },
    { name: "Mapawa", beneficiaries: "2,870", amount: "251,624" },
    { name: "Maragusan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mauswagon", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Albay", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Katipunan", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Manay", beneficiaries: "2,124", amount: "152,724" },
    { name: "New Panay", beneficiaries: "2,124", amount: "193,513" },
    { name: "Paloc", beneficiaries: "1,972", amount: "212,523" },
    { name: "Pamintaran", beneficiaries: "1,241", amount: "183,523" },
    { name: "Parasanon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Talian", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tandik", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tigbao", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tupas", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
