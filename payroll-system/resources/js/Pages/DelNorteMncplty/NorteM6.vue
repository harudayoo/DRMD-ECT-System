<template>
    <BTemp
        :pageTitle="'List of Barangays in Panabo, Davao del Norte'"
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
    { name: "A. O. Floirendo", beneficiaries: "1,245", amount: "102,673" },
    { name: "Buenavista", beneficiaries: "1,045", amount: "207,135" },
    { name: "Cacao", beneficiaries: "3,764", amount: "245,235" },
    { name: "Cagangohan", beneficiaries: "2,983", amount: "215,064" },
    { name: "Consolacion", beneficiaries: "1,870", amount: "211,233" },
    { name: "Dapco", beneficiaries: "3,513", amount: "251,123" },
    { name: "Datu Abdul Dadia", beneficiaries: "2,124", amount: "152,724" },
    { name: "Gredu", beneficiaries: "2,124", amount: "193,513" },
    { name: "J. P. Laurel", beneficiaries: "1,972", amount: "212,523" },
    { name: "Kasilak", beneficiaries: "1,241", amount: "183,523" },
    { name: "Katipunan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Katualan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kauswagan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kiotoy", beneficiaries: "1,045", amount: "207,135" },
    { name: "Little Panay", beneficiaries: "3,764", amount: "245,235" },
    { name: "Lower Panaga", beneficiaries: "2,983", amount: "215,064" },
    { name: "Mabunao", beneficiaries: "1,870", amount: "211,233" },
    { name: "Malativas", beneficiaries: "3,513", amount: "251,123" },
    { name: "Manay", beneficiaries: "2,124", amount: "152,724" },
    { name: "Nanyo", beneficiaries: "2,124", amount: "193,513" },
    { name: "New Malaga", beneficiaries: "1,972", amount: "212,523" },
    { name: "New Malitbog", beneficiaries: "1,241", amount: "183,523" },
    { name: "New Pandan", beneficiaries: "2,870", amount: "251,624" },
    { name: "New Visayas", beneficiaries: "2,870", amount: "251,624" },
    { name: "Quezon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Salvacion", beneficiaries: "1,045", amount: "207,135" },
    { name: "San Francisco", beneficiaries: "3,764", amount: "245,235" },
    { name: "San Nicolas", beneficiaries: "2,983", amount: "215,064" },
    { name: "San Pedro", beneficiaries: "1,870", amount: "211,233" },
    { name: "San Roque", beneficiaries: "3,513", amount: "251,123" },
    { name: "San Vicente", beneficiaries: "2,124", amount: "152,724" },
    { name: "Santa Cruz", beneficiaries: "2,124", amount: "193,513" },
    { name: "Santo Niño", beneficiaries: "1,972", amount: "212,523" },
    { name: "Sindaton", beneficiaries: "1,241", amount: "183,523" },
    { name: "Southern Davao", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tagpore", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tibungol", beneficiaries: "2,870", amount: "251,624" },
    { name: "Upper Licanan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Waterfall", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
