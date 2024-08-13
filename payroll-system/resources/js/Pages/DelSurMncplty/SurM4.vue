<template>
    <BTemp
        :pageTitle="'List of Barangays in Hagonoy, Davao del Sur'"
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
    { name: "Balutakay", beneficiaries: "1,245", amount: "102,673" },
    { name: "Clib", beneficiaries: "1,045", amount: "207,135" },
    { name: "Guihing", beneficiaries: "3,764", amount: "245,235" },
    { name: "Guihing Aplaya", beneficiaries: "2,983", amount: "215,064" },
    { name: "Hagonoy Crossing", beneficiaries: "1,870", amount: "211,233" },
    { name: "Kibuaya", beneficiaries: "3,513", amount: "251,123" },
    { name: "La Union", beneficiaries: "2,124", amount: "152,724" },
    { name: "Lanuro", beneficiaries: "2,124", amount: "193,513" },
    { name: "Lapulabao", beneficiaries: "1,972", amount: "212,523" },
    { name: "Leling", beneficiaries: "1,241", amount: "183,523" },
    { name: "Mahayahay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Malabang Damsite", beneficiaries: "2,870", amount: "251,624" },
    { name: "Maliit Digos", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Quezon", beneficiaries: "3,513", amount: "251,123" },
    { name: "Paligue", beneficiaries: "2,124", amount: "152,724" },
    { name: "Poblacion", beneficiaries: "2,124", amount: "193,513" },
    { name: "Sacub", beneficiaries: "1,972", amount: "212,523" },
    { name: "San Guillermo", beneficiaries: "1,241", amount: "183,523" },
    { name: "San Isidro", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sinayawan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tologan", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
