<template>
    <BTemp
        :pageTitle="'List of Barangays in Baganga, Davao Oriental'"
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
    { name: "Baculin", beneficiaries: "1,245", amount: "102,673" },
    { name: "Banao", beneficiaries: "1,045", amount: "207,135" },
    { name: "Batawan", beneficiaries: "3,764", amount: "245,235" },
    { name: "Batiano", beneficiaries: "1,870", amount: "211,233" },
    { name: "Binondo", beneficiaries: "3,513", amount: "251,123" },
    { name: "Bobonao", beneficiaries: "2,124", amount: "152,724" },
    { name: "Campawan", beneficiaries: "2,124", amount: "193,513" },
    { name: "Central", beneficiaries: "1,972", amount: "212,523" },
    { name: "Dapnan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Kinablangan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Lambajon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Lucod", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mahanub", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mikit", beneficiaries: "2,870", amount: "251,624" },
    { name: "Salingcomot", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Isidro", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Victor", beneficiaries: "2,870", amount: "251,624" },
    { name: "Saoquegue", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
