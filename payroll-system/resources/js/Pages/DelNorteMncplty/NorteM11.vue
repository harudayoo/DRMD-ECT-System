<template>
    <BTemp
        :pageTitle="'List of Barangays in Talaingod, Davao del Norte'"
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
    { name: "Dagohoy", beneficiaries: "1,245", amount: "102,673" },
    { name: "Palma Gil", beneficiaries: "1,045", amount: "207,135" },
    { name: "Santo Niño", beneficiaries: "3,764", amount: "245,235" },
]);
</script>
