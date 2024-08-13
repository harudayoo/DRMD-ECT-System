<template>
    <BTemp
        :pageTitle="'List of Barangays in Braulio E. Dujali, Davao del Norte'"
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
    { name: "Cabayangan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Dujali", beneficiaries: "1,045", amount: "207,135" },
    { name: "Magupising", beneficiaries: "3,764", amount: "245,235" },
    { name: "New Casay", beneficiaries: "2,983", amount: "215,064" },
    { name: "Tanglaw", beneficiaries: "1,870", amount: "211,233" },
]);
</script>
