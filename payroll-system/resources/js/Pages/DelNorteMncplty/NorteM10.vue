<template>
    <BTemp
        :pageTitle="'List of Barangays in Tagum, Davao del Norte'"
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
    { name: "Apokon", beneficiaries: "1,245", amount: "102,673" },
    { name: "Bincungan", beneficiaries: "1,045", amount: "207,135" },
    { name: "Busaon", beneficiaries: "3,764", amount: "245,235" },
    { name: "Canocotan", beneficiaries: "2,983", amount: "215,064" },
    { name: "Cuambogan", beneficiaries: "1,870", amount: "211,233" },
    { name: "La Filipina", beneficiaries: "3,513", amount: "251,123" },
    { name: "Liboganon", beneficiaries: "2,124", amount: "152,724" },
    { name: "Madaum", beneficiaries: "2,124", amount: "193,513" },
    { name: "Magdum", beneficiaries: "1,972", amount: "212,523" },
    { name: "Magugpo East", beneficiaries: "1,241", amount: "183,523" },
    { name: "Magugpo North", beneficiaries: "2,870", amount: "251,624" },
    { name: "Magugpo Poblacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "Magugpo South", beneficiaries: "2,870", amount: "251,624" },
    { name: "Magugpo West", beneficiaries: "1,045", amount: "207,135" },
    { name: "Mankilam", beneficiaries: "3,764", amount: "245,235" },
    { name: "New Balamban", beneficiaries: "2,983", amount: "215,064" },
    { name: "Nueva Fuerza", beneficiaries: "1,870", amount: "211,233" },
    { name: "Pagsabangan", beneficiaries: "3,513", amount: "251,123" },
    { name: "Pandapan", beneficiaries: "2,124", amount: "152,724" },
    { name: "San Agustin", beneficiaries: "2,124", amount: "193,513" },
    { name: "San Isidro", beneficiaries: "1,972", amount: "212,523" },
    { name: "San Miguel", beneficiaries: "1,241", amount: "183,523" },
    { name: "Visayan Village", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
