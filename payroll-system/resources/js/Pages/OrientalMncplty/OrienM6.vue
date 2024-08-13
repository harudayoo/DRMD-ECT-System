<template>
    <BTemp
        :pageTitle="'List of Barangays in Governor Generoso, Davao Oriental'"
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
    { name: "Anitap", beneficiaries: "1,245", amount: "102,673" },
    { name: "Crispin Dela Cruz", beneficiaries: "1,045", amount: "207,135" },
    { name: "Don Aurelio Chicote", beneficiaries: "3,764", amount: "245,235" },
    { name: "Lavigan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Luzon", beneficiaries: "3,513", amount: "251,123" },
    { name: "Magdug", beneficiaries: "2,124", amount: "152,724" },
    { name: "Manuel Roxas", beneficiaries: "2,124", amount: "193,513" },
    { name: "Monserrat", beneficiaries: "1,972", amount: "212,523" },
    { name: "Nangan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Oregon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Poblacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "Pundaguitan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sergio Osmeña", beneficiaries: "2,870", amount: "251,624" },
    { name: "Surop", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tagabebe", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tamabn", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tandang Sora", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tibanban", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tiblawan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Upper Tibanban", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
