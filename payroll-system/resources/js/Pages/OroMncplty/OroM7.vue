<template>
    <BTemp
        :pageTitle="'List of Barangays in Monkayo, Davao de Oro'"
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
    { name: "Awao", beneficiaries: "1,245", amount: "102,673" },
    { name: "Babag", beneficiaries: "1,045", amount: "207,135" },
    { name: "Banlag", beneficiaries: "3,764", amount: "245,235" },
    { name: "Baylo", beneficiaries: "2,983", amount: "215,064" },
    { name: "Casoon", beneficiaries: "1,870", amount: "211,233" },
    { name: "Haguimitan", beneficiaries: "3,513", amount: "251,123" },
    { name: "Inambatan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Macopa", beneficiaries: "2,124", amount: "193,513" },
    { name: "Mamunga", beneficiaries: "1,972", amount: "212,523" },
    { name: "Mount Diwata", beneficiaries: "1,241", amount: "183,523" },
    { name: "Naboc", beneficiaries: "2,870", amount: "251,624" },
    { name: "Olaycon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Pasian", beneficiaries: "2,870", amount: "251,624" },
    { name: "Poblacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "Rizal", beneficiaries: "2,870", amount: "251,624" },
    { name: "Salvacion", beneficiaries: "1,241", amount: "183,523" },
    { name: "San Isidro", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Jose", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tubo-tubo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Union", beneficiaries: "2,870", amount: "251,624" },
    { name: "Upper Ulip", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
