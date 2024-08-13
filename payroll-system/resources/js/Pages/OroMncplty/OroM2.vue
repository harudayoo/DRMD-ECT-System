<template>
    <BTemp
        :pageTitle="'List of Barangays in Laak, Davao de Oro'"
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
    { name: "Aguinaldo", beneficiaries: "1,245", amount: "102,673" },
    { name: "Amor Cruz", beneficiaries: "1,045", amount: "207,135" },
    { name: "Ampawid", beneficiaries: "3,764", amount: "245,235" },
    { name: "Andap", beneficiaries: "2,983", amount: "215,064" },
    { name: "Anitap", beneficiaries: "1,870", amount: "211,233" },
    { name: "Bagong Silang", beneficiaries: "3,513", amount: "251,123" },
    { name: "Banbanon", beneficiaries: "2,124", amount: "152,724" },
    { name: "Belmonte", beneficiaries: "2,124", amount: "193,513" },
    { name: "Binasbas", beneficiaries: "1,972", amount: "212,523" },
    { name: "Bullucan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Cebulida", beneficiaries: "2,870", amount: "251,624" },
    { name: "Concepcion", beneficiaries: "2,870", amount: "251,624" },
    { name: "Datu Ampunan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Datu Davao", beneficiaries: "2,870", amount: "251,624" },
    { name: "Doña Josefa", beneficiaries: "2,870", amount: "251,624" },
    { name: "El Katipunan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Il Papa", beneficiaries: "1,045", amount: "207,135" },
    { name: "Imelda", beneficiaries: "3,764", amount: "245,235" },
    { name: "Inacayan", beneficiaries: "2,983", amount: "215,064" },
    { name: "Kaligutan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Kapatagan", beneficiaries: "3,513", amount: "251,123" },
    { name: "Kidawa", beneficiaries: "2,124", amount: "152,724" },
    { name: "Kilagding", beneficiaries: "2,124", amount: "193,513" },
    { name: "Kiokmay", beneficiaries: "1,972", amount: "212,523" },
    { name: "Laac", beneficiaries: "1,241", amount: "183,523" },
    { name: "Langtud", beneficiaries: "2,870", amount: "251,624" },
    { name: "Longanapan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mabuhay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Macopa", beneficiaries: "2,870", amount: "251,624" },
    { name: "Malinao", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mangloy", beneficiaries: "1,241", amount: "183,523" },
    { name: "Melale", beneficiaries: "2,870", amount: "251,624" },
    { name: "Naga", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Bethlehem", beneficiaries: "2,870", amount: "251,624" },
    { name: "Panamoren", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sabud", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Antonio", beneficiaries: "1,241", amount: "183,523" },
    { name: "Santa Emilia", beneficiaries: "2,870", amount: "251,624" },
    { name: "Santo Niño", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sisimon", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
