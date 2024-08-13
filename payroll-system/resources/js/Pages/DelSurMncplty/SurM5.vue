<template>
    <BTemp
        :pageTitle="'List of Barangays in Kiblawan, Davao del Sur'"
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
    { name: "Abnate", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bagong Negros", beneficiaries: "1,045", amount: "207,135" },
    { name: "Bagong Silang", beneficiaries: "3,764", amount: "245,235" },
    { name: "Bagumbayan", beneficiaries: "2,983", amount: "215,064" },
    { name: "Balasiao", beneficiaries: "1,870", amount: "211,233" },
    { name: "Bonifacio", beneficiaries: "3,513", amount: "251,123" },
    { name: "Bulol-Salo", beneficiaries: "2,124", amount: "152,724" },
    { name: "Bunot", beneficiaries: "2,124", amount: "193,513" },
    { name: "Cogon-Bacaca", beneficiaries: "1,972", amount: "212,523" },
    { name: "Dapok", beneficiaries: "1,241", amount: "183,523" },
    { name: "Ihan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kibongbong", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kimlawis", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kisulan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Lati-an", beneficiaries: "1,045", amount: "207,135" },
    { name: "Manual", beneficiaries: "2,983", amount: "215,064" },
    { name: "Maraga-a", beneficiaries: "1,870", amount: "211,233" },
    { name: "Molopolo", beneficiaries: "3,513", amount: "251,123" },
    { name: "New Sibonga", beneficiaries: "2,124", amount: "152,724" },
    { name: "Panaglib", beneficiaries: "2,124", amount: "193,513" },
    { name: "Pasig", beneficiaries: "1,972", amount: "212,523" },
    { name: "Poblacion", beneficiaries: "1,241", amount: "183,523" },
    { name: "Pocaleel", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Isidro", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Jose", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Pedro", beneficiaries: "2,124", amount: "193,513" },
    { name: "Santo Niño", beneficiaries: "1,972", amount: "212,523" },
    { name: "Tacub", beneficiaries: "1,241", amount: "183,523" },
    { name: "Tacul", beneficiaries: "2,870", amount: "251,624" },
    { name: "Waterfall", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
