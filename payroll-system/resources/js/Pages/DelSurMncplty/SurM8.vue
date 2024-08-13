<template>
    <BTemp
        :pageTitle="'List of Barangays in Matanao, Davao del Sur'"
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
    { name: "Asbang", beneficiaries: "1,245", amount: "102,673" },
    { name: "Asinan", beneficiaries: "1,045", amount: "207,135" },
    { name: "Bagumbayan", beneficiaries: "3,764", amount: "245,235" },
    { name: "Bangkal", beneficiaries: "2,983", amount: "215,064" },
    { name: "Buas", beneficiaries: "1,870", amount: "211,233" },
    { name: "Buri", beneficiaries: "3,513", amount: "251,123" },
    { name: "Cabligan", beneficiaries: "2,124", amount: "152,724" },
    { name: "Camanchiles", beneficiaries: "2,124", amount: "193,513" },
    { name: "Ceboza", beneficiaries: "1,972", amount: "212,523" },
    { name: "Colonsabak", beneficiaries: "1,241", amount: "183,523" },
    { name: "Dongan-Pekong", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kabasagan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kapok", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kauswagan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Kibao", beneficiaries: "1,045", amount: "207,135" },
    { name: "La Suerte", beneficiaries: "3,764", amount: "245,235" },
    { name: "Langa-an", beneficiaries: "2,983", amount: "215,064" },
    { name: "Lower Marber", beneficiaries: "1,870", amount: "211,233" },
    { name: "Manga", beneficiaries: "3,513", amount: "251,123" },
    { name: "New Katipunana", beneficiaries: "2,124", amount: "193,513" },
    { name: "New Murica", beneficiaries: "1,972", amount: "212,523" },
    { name: "New Visayas", beneficiaries: "1,241", amount: "183,523" },
    { name: "Poblacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "Saboy", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Jose", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Miguel", beneficiaries: "2,124", amount: "193,513" },
    { name: "San Vicente", beneficiaries: "1,972", amount: "212,523" },
    { name: "Saub", beneficiaries: "1,241", amount: "183,523" },
    { name: "Sinaragan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Sinawilan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tamlangon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tibongbong", beneficiaries: "2,870", amount: "251,624" },
    { name: "Towak", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
