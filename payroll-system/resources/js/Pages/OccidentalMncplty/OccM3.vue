<template>
    <BTemp
        :pageTitle="'List of Barangays in Malita, Davao Occidental'"
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
    { name: "Bito", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bolila", beneficiaries: "1,045", amount: "207,135" },
    { name: "Buhangin", beneficiaries: "3,764", amount: "245,235" },
    { name: "Culaman", beneficiaries: "2,983", amount: "215,064" },
    { name: "Datu Danwata", beneficiaries: "1,870", amount: "211,233" },
    { name: "Demoloc", beneficiaries: "3,513", amount: "251,123" },
    { name: "Felis", beneficiaries: "2,124", amount: "152,724" },
    { name: "Fishing Village", beneficiaries: "2,124", amount: "193,513" },
    { name: "Kibalatong", beneficiaries: "1,972", amount: "212,523" },
    { name: "Kidalapong", beneficiaries: "1,241", amount: "183,523" },
    { name: "Kilalag", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kinangan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Lacaron", beneficiaries: "2,870", amount: "251,624" },
    { name: "Lagumit", beneficiaries: "2,870", amount: "251,624" },
    { name: "Lais", beneficiaries: "2,870", amount: "251,624" },
    { name: "Little Baguio", beneficiaries: "1,245", amount: "102,673" },
    { name: "Macol", beneficiaries: "1,045", amount: "207,135" },
    { name: "Mana", beneficiaries: "3,764", amount: "245,235" },
    { name: "Manuel Peralta", beneficiaries: "2,983", amount: "215,064" },
    { name: "New Argao", beneficiaries: "1,870", amount: "211,233" },
    { name: "Pangaleon", beneficiaries: "3,513", amount: "251,123" },
    { name: "Pangian", beneficiaries: "2,124", amount: "152,724" },
    { name: "Pinalpalan", beneficiaries: "2,124", amount: "193,513" },
    { name: "Poblacion", beneficiaries: "1,972", amount: "212,523" },
    { name: "Sangay", beneficiaries: "1,241", amount: "183,523" },
    { name: "Talogoy", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tical", beneficiaries: "2,870", amount: "251,624" },
    { name: "Ticulon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tingolo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tubalan", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
