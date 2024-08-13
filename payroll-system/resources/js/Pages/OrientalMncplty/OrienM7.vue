<template>
    <BTemp
        :pageTitle="'List of Barangays in Lupon, Davao Oriental'"
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
    { name: "Bagumbayan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Cabadiangan", beneficiaries: "1,045", amount: "207,135" },
    { name: "Calapagan", beneficiaries: "3,764", amount: "245,235" },
    { name: "Cocornon", beneficiaries: "1,870", amount: "211,233" },
    { name: "Corporacion", beneficiaries: "3,513", amount: "251,123" },
    { name: "Don Mariano Marcos", beneficiaries: "2,124", amount: "152,724" },
    { name: "Ilangay", beneficiaries: "2,124", amount: "193,513" },
    { name: "Langka", beneficiaries: "1,972", amount: "212,523" },
    { name: "Lantawan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Limbahan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Macangao", beneficiaries: "2,870", amount: "251,624" },
    { name: "Magsaysay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mahayahay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Maragatas", beneficiaries: "2,870", amount: "251,624" },
    { name: "Marayag", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Visayas", beneficiaries: "2,870", amount: "251,624" },
    { name: "Poblacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Isidro", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Jose", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tagbao", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tagugpo", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
