<template>
    <BTemp
        :pageTitle="'List of Barangays in Jose Abad Santos, Davao Occidental'"
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
    { name: "Balangonan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Buguis", beneficiaries: "1,045", amount: "207,135" },
    { name: "Bukid", beneficiaries: "3,764", amount: "245,235" },
    { name: "Butuan", beneficiaries: "2,983", amount: "215,064" },
    { name: "Butulan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Caburan Big", beneficiaries: "3,513", amount: "251,123" },
    { name: "Caburan Small", beneficiaries: "2,124", amount: "152,724" },
    { name: "Camalian", beneficiaries: "2,124", amount: "193,513" },
    { name: "Carahayan", beneficiaries: "1,972", amount: "212,523" },
    { name: "Cayaponga", beneficiaries: "1,241", amount: "183,523" },
    { name: "Culaman", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kalbay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Kitayo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Magulibas", beneficiaries: "2,870", amount: "251,624" },
    { name: "Malalan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mangile", beneficiaries: "1,045", amount: "207,135" },
    { name: "Marabutuan", beneficiaries: "3,764", amount: "245,235" },
    { name: "Meybio", beneficiaries: "2,983", amount: "215,064" },
    { name: "Molmol", beneficiaries: "1,870", amount: "211,233" },
    { name: "Nuing", beneficiaries: "3,513", amount: "251,123" },
    { name: "Patulang", beneficiaries: "2,124", amount: "152,724" },
    { name: "Quiapo", beneficiaries: "2,124", amount: "193,513" },
    { name: "San Isidro", beneficiaries: "1,972", amount: "212,523" },
    { name: "Sugal", beneficiaries: "1,241", amount: "183,523" },
    { name: "Tabayon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Tanuman", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
