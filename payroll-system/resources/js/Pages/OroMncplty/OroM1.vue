<template>
    <BTemp
        :pageTitle="'List of Barangays in Compostela, Davao de Oro'"
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
    { name: "Aurora", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bagongon", beneficiaries: "1,045", amount: "207,135" },
    { name: "Gabi", beneficiaries: "3,764", amount: "245,235" },
    { name: "Lagab", beneficiaries: "2,983", amount: "215,064" },
    { name: "Mangayon", beneficiaries: "1,870", amount: "211,233" },
    { name: "Mapaca", beneficiaries: "3,513", amount: "251,123" },
    { name: "New Alegria", beneficiaries: "2,124", amount: "152,724" },
    { name: "Ngan", beneficiaries: "2,124", amount: "193,513" },
    { name: "Osmeña", beneficiaries: "1,972", amount: "212,523" },
    { name: "Pansalan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Poblacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Jose", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Miguel", beneficiaries: "2,870", amount: "251,624" },
    { name: "Siocon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tamia", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
