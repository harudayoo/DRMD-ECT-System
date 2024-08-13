<template>
    <BTemp
        :pageTitle="'List of Barangays in Nabunturan, Davao de Oro'"
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
    { name: "Anislagan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Antequera", beneficiaries: "1,045", amount: "207,135" },
    { name: "Basak", beneficiaries: "3,764", amount: "245,235" },
    { name: "Bayabas", beneficiaries: "2,983", amount: "215,064" },
    { name: "Bukal", beneficiaries: "1,870", amount: "211,233" },
    { name: "Cabacungan", beneficiaries: "3,513", amount: "251,123" },
    { name: "Cabidianan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Katipunan", beneficiaries: "2,124", amount: "193,513" },
    { name: "Libasan", beneficiaries: "1,972", amount: "212,523" },
    { name: "Linda", beneficiaries: "1,241", amount: "183,523" },
    { name: "Magading", beneficiaries: "2,870", amount: "251,624" },
    { name: "Magsaysay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mainit", beneficiaries: "2,870", amount: "251,624" },
    { name: "Manat", beneficiaries: "2,870", amount: "251,624" },
    { name: "Matilo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mipangi", beneficiaries: "3,764", amount: "245,235" },
    { name: "New Dauis", beneficiaries: "2,983", amount: "215,064" },
    { name: "New Sibonga", beneficiaries: "1,870", amount: "211,233" },
    { name: "Ogao", beneficiaries: "3,513", amount: "251,123" },
    { name: "Pangutosan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Poblacion", beneficiaries: "2,124", amount: "193,513" },
    { name: "San Isidro", beneficiaries: "1,972", amount: "212,523" },
    { name: "San Roque", beneficiaries: "1,241", amount: "183,523" },
    { name: "San Vicente", beneficiaries: "2,870", amount: "251,624" },
    { name: "Santa Maria", beneficiaries: "2,870", amount: "251,624" },
    { name: "Santo Niño", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sasa", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tagnocon", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
