<template>
    <BTemp
        :pageTitle="'List of Barangays in Pantukan, Davao de Oro'"
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
    { name: "Araibo", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bongabong", beneficiaries: "1,045", amount: "207,135" },
    { name: "Bongbong", beneficiaries: "3,764", amount: "245,235" },
    { name: "Kingking", beneficiaries: "2,983", amount: "215,064" },
    { name: "Las Arenas", beneficiaries: "1,870", amount: "211,233" },
    { name: "Magnaga", beneficiaries: "3,513", amount: "251,123" },
    { name: "Matiao", beneficiaries: "2,124", amount: "152,724" },
    { name: "Napnapan", beneficiaries: "2,124", amount: "193,513" },
    { name: "P. Fuentes", beneficiaries: "1,972", amount: "212,523" },
    { name: "Tagdangua", beneficiaries: "1,241", amount: "183,523" },
    { name: "Tag-ugpo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tambongon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tibagon", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
