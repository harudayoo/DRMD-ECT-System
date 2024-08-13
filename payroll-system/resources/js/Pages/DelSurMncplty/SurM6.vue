<template>
    <BTemp
        :pageTitle="'List of Barangays in Magsaysay, Davao del Sur'"
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
    { name: "Bacungan", beneficiaries: "1,245", amount: "102,673" },
    { name: "Balnate", beneficiaries: "1,045", amount: "207,135" },
    { name: "Barayong", beneficiaries: "3,764", amount: "245,235" },
    { name: "Blocon", beneficiaries: "2,983", amount: "215,064" },
    { name: "Dalawinon", beneficiaries: "1,870", amount: "211,233" },
    { name: "Dalumay", beneficiaries: "3,513", amount: "251,123" },
    { name: "Glamang", beneficiaries: "2,124", amount: "152,724" },
    { name: "Kanapulo", beneficiaries: "2,124", amount: "193,513" },
    { name: "Kasuga", beneficiaries: "1,972", amount: "212,523" },
    { name: "Lower Bala", beneficiaries: "1,241", amount: "183,523" },
    { name: "Mabini", beneficiaries: "2,870", amount: "251,624" },
    { name: "Maibo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Malawanit", beneficiaries: "2,870", amount: "251,624" },
    { name: "Malongon", beneficiaries: "1,972", amount: "212,523" },
    { name: "New Ilocos", beneficiaries: "1,241", amount: "183,523" },
    { name: "New Opon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Poblacion", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Isidro", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Miguel", beneficiaries: "1,972", amount: "212,523" },
    { name: "Tacul", beneficiaries: "1,241", amount: "183,523" },
    { name: "Tagaytay", beneficiaries: "2,870", amount: "251,624" },
    { name: "Upper Bala", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
