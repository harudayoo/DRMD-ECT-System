<template>
    <BTemp
        :pageTitle="'List of Barangays in San Isidro, Davao del Norte'"
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
    { name: "Dacudao", beneficiaries: "1,245", amount: "102,673" },
    { name: "Datu Balong", beneficiaries: "1,045", amount: "207,135" },
    { name: "Igangon", beneficiaries: "3,764", amount: "245,235" },
    { name: "Kipalili", beneficiaries: "2,983", amount: "215,064" },
    { name: "Libuton", beneficiaries: "1,870", amount: "211,233" },
    { name: "Linao", beneficiaries: "3,513", amount: "251,123" },
    { name: "Mamangan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Monte Dujali", beneficiaries: "2,124", amount: "193,513" },
    { name: "Pinamuno", beneficiaries: "1,972", amount: "212,523" },
    { name: "Sabangan", beneficiaries: "1,241", amount: "183,523" },
    { name: "San Miguel ", beneficiaries: "2,870", amount: "251,624" },
    { name: "Santo Niño", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sawata", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
