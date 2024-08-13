<template>
    <BTemp
        :pageTitle="'List of Barangays in Cateel, Davao Oriental'"
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
    { name: "Abijod", beneficiaries: "1,245", amount: "102,673" },
    { name: "Alegria", beneficiaries: "1,045", amount: "207,135" },
    { name: "Aliwagwag", beneficiaries: "3,764", amount: "245,235" },
    { name: "Aragon", beneficiaries: "1,870", amount: "211,233" },
    { name: "Baybay", beneficiaries: "3,513", amount: "251,123" },
    { name: "Maglahus", beneficiaries: "2,124", amount: "152,724" },
    { name: "Mainit", beneficiaries: "2,124", amount: "193,513" },
    { name: "Malibago", beneficiaries: "1,972", amount: "212,523" },
    { name: "Poblacion", beneficiaries: "1,241", amount: "183,523" },
    { name: "San Alfonso", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Antonio", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Miguel", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Rafael", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Vicente", beneficiaries: "2,870", amount: "251,624" },
    { name: "Santa Filomena", beneficiaries: "2,870", amount: "251,624" },
    { name: "Taytayan", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
