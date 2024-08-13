<template>
    <BTemp
        :pageTitle="'List of Barangays in Digos, Davao del Sur'"
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
    { name: "Aplaya", beneficiaries: "1,245", amount: "102,673" },
    { name: "Balabag", beneficiaries: "1,045", amount: "207,135" },
    { name: "Binaton", beneficiaries: "3,764", amount: "245,235" },
    { name: "Cogon", beneficiaries: "2,983", amount: "215,064" },
    { name: "Colorado", beneficiaries: "1,870", amount: "211,233" },
    { name: "Dawis", beneficiaries: "3,513", amount: "251,123" },
    { name: "Dulangan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Goma", beneficiaries: "2,124", amount: "193,513" },
    { name: "Igpit", beneficiaries: "1,972", amount: "212,523" },
    { name: "Kapatagan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Kiagot", beneficiaries: "2,870", amount: "251,624" },
    { name: "Lungag", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mahayahay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Matti", beneficiaries: "2,983", amount: "215,064" },
    { name: "Ruparan", beneficiaries: "1,870", amount: "211,233" },
    { name: "San Agustin", beneficiaries: "3,513", amount: "251,123" },
    { name: "San Jose", beneficiaries: "2,124", amount: "152,724" },
    { name: "San Miguel", beneficiaries: "2,124", amount: "193,513" },
    { name: "San Roque", beneficiaries: "1,972", amount: "212,523" },
    { name: "Sinawilan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Soong", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tiguman", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tres de Mayo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Zone 1", beneficiaries: "2,870", amount: "251,624" },
    { name: "Zone 2", beneficiaries: "2,870", amount: "251,624" },
    { name: "Zone 3", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
