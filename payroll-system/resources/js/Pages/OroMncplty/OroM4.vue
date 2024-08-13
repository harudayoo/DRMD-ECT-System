<template>
    <BTemp
        :pageTitle="'List of Barangays in Maco, Davao de Oro'"
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
    { name: "Anibongan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Anislagan", beneficiaries: "1,045", amount: "207,135" },
    { name: "Binuangan", beneficiaries: "3,764", amount: "245,235" },
    { name: "Bucana", beneficiaries: "2,983", amount: "215,064" },
    { name: "Calabcab", beneficiaries: "1,870", amount: "211,233" },
    { name: "Concepcion", beneficiaries: "3,513", amount: "251,123" },
    { name: "Dumlan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Elizalde", beneficiaries: "2,124", amount: "193,513" },
    { name: "Gubatan", beneficiaries: "1,972", amount: "212,523" },
    { name: "Hijo", beneficiaries: "1,241", amount: "183,523" },
    { name: "Kinuban", beneficiaries: "2,870", amount: "251,624" },
    { name: "Langgam", beneficiaries: "2,870", amount: "251,624" },
    { name: "Lapu-lapu", beneficiaries: "2,870", amount: "251,624" },
    { name: "Libay-libay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Limbo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Lumatab", beneficiaries: "1,245", amount: "102,673" },
    { name: "Magangit", beneficiaries: "1,045", amount: "207,135" },
    { name: "Mainit", beneficiaries: "3,764", amount: "245,235" },
    { name: "Malamodao", beneficiaries: "2,983", amount: "215,064" },
    { name: "Manipongol", beneficiaries: "1,870", amount: "211,233" },
    { name: "Mapaang", beneficiaries: "3,513", amount: "251,123" },
    { name: "Masara", beneficiaries: "2,124", amount: "152,724" },
    { name: "New Asturias", beneficiaries: "2,124", amount: "193,513" },
    { name: "New Barili", beneficiaries: "1,972", amount: "212,523" },
    { name: "New Leyte", beneficiaries: "1,241", amount: "183,523" },
    { name: "New Visayas", beneficiaries: "2,870", amount: "251,624" },
    { name: "Panangan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Pangi", beneficiaries: "2,870", amount: "251,624" },
    { name: "Panibasan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Panoraon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Poblacion", beneficiaries: "1,972", amount: "212,523" },
    { name: "San Juan", beneficiaries: "1,241", amount: "183,523" },
    { name: "San Roque", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sangab", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tagbaros", beneficiaries: "2,870", amount: "251,624" },
    { name: "Taglawig", beneficiaries: "2,870", amount: "251,624" },
    { name: "Teresa", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
