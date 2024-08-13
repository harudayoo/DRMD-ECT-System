<template>
    <BTemp
        :pageTitle="'List of Barangays in Don Marcelino, Davao Occidental'"
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
    { name: "Baluntaya", beneficiaries: "1,245", amount: "102,673" },
    { name: "Calian", beneficiaries: "1,045", amount: "207,135" },
    { name: "Dalupan", beneficiaries: "3,764", amount: "245,235" },
    { name: "Kinanga", beneficiaries: "1,870", amount: "211,233" },
    { name: "Kiobog", beneficiaries: "3,513", amount: "251,123" },
    { name: "Lanao", beneficiaries: "2,124", amount: "152,724" },
    { name: "Lapuan", beneficiaries: "2,124", amount: "193,513" },
    { name: "Lawa", beneficiaries: "1,972", amount: "212,523" },
    { name: "Linadasan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Mabuhay", beneficiaries: "2,870", amount: "251,624" },
    { name: "North Lamidan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Nueva Villa", beneficiaries: "2,870", amount: "251,624" },
    { name: "South Lamidan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Talagutong", beneficiaries: "2,870", amount: "251,624" },
    { name: "West Lamidan", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
