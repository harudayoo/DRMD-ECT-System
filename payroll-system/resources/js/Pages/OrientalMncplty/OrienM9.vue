<template>
    <BTemp
        :pageTitle="'List of Barangays in Mati, Davao Oriental'"
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
    { name: "Badas", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bobon", beneficiaries: "1,045", amount: "207,135" },
    { name: "Buso", beneficiaries: "1,870", amount: "211,233" },
    { name: "Cabuaya", beneficiaries: "3,513", amount: "251,123" },
    { name: "Central", beneficiaries: "2,124", amount: "152,724" },
    { name: "Culian", beneficiaries: "2,124", amount: "193,513" },
    { name: "Dahican", beneficiaries: "1,972", amount: "212,523" },
    { name: "Danao", beneficiaries: "1,241", amount: "183,523" },
    { name: "Dawan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Don Enrique Lopez", beneficiaries: "2,870", amount: "251,624" },
    { name: "Don Martin Marundan", beneficiaries: "2,870", amount: "251,624" },
    {
        name: "Don Salvador Lopez, Sr.",
        beneficiaries: "2,870",
        amount: "251,624",
    },
    { name: "Langka", beneficiaries: "2,870", amount: "251,624" },
    { name: "Lawigan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Libudon", beneficiaries: "3,513", amount: "251,123" },
    { name: "Luban", beneficiaries: "2,124", amount: "152,724" },
    { name: "Macambol", beneficiaries: "2,124", amount: "193,513" },
    { name: "Mamali", beneficiaries: "1,972", amount: "212,523" },
    { name: "Matiao", beneficiaries: "1,241", amount: "183,523" },
    { name: "Mayo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sainz", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sanghay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tagabakid", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tagbinonga", beneficiaries: "2,870", amount: "251,624" },
    { name: "Taguibo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tamisan", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
