<template>
    <BTemp
        :pageTitle="'List of Barangays in Samal, Davao del Norte'"
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
    { name: "Adecor", beneficiaries: "1,245", amount: "102,673" },
    { name: "Anonang", beneficiaries: "1,045", amount: "207,135" },
    { name: "Aumbay", beneficiaries: "3,764", amount: "245,235" },
    { name: "Aundanao", beneficiaries: "2,983", amount: "215,064" },
    { name: "Balet", beneficiaries: "1,870", amount: "211,233" },
    { name: "Bandera", beneficiaries: "3,513", amount: "251,123" },
    { name: "Caliclic", beneficiaries: "2,124", amount: "152,724" },
    { name: "Camudmud", beneficiaries: "2,124", amount: "193,513" },
    { name: "Catagman", beneficiaries: "1,972", amount: "212,523" },
    { name: "Cawag", beneficiaries: "1,241", amount: "183,523" },
    { name: "Cogon", beneficiaries: "2,870", amount: "251,624" },
    { name: "Cogon (Talicod)", beneficiaries: "2,870", amount: "251,624" },
    { name: "Dadatan", beneficiaries: "2,870", amount: "251,624" },
    { name: "Del Monte", beneficiaries: "1,045", amount: "207,135" },
    { name: "Guilon", beneficiaries: "3,764", amount: "245,235" },
    { name: "Kanaan", beneficiaries: "2,983", amount: "215,064" },
    { name: "Kinawitnon", beneficiaries: "1,870", amount: "211,233" },
    { name: "Libertad", beneficiaries: "3,513", amount: "251,123" },
    { name: "Libuak", beneficiaries: "2,124", amount: "152,724" },
    { name: "Licup", beneficiaries: "2,124", amount: "193,513" },
    { name: "Limao", beneficiaries: "1,972", amount: "212,523" },
    { name: "Linosutan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Mambago-A", beneficiaries: "2,870", amount: "251,624" },
    { name: "Mambago-B", beneficiaries: "2,870", amount: "251,624" },
    { name: "Miranda", beneficiaries: "2,870", amount: "251,624" },
    { name: "Moncado", beneficiaries: "2,983", amount: "215,064" },
    { name: "Pangubatan", beneficiaries: "1,870", amount: "211,233" },
    { name: "Peñaplata", beneficiaries: "3,513", amount: "251,123" },
    { name: "Poblacion", beneficiaries: "2,124", amount: "152,724" },
    { name: "San Agustin", beneficiaries: "2,124", amount: "193,513" },
    { name: "San Antonio", beneficiaries: "1,972", amount: "212,523" },
    { name: "San Isidro (Babak)", beneficiaries: "1,241", amount: "183,523" },
    {
        name: "San Isidro (Kaputian)",
        beneficiaries: "2,870",
        amount: "251,624",
    },
    { name: "San Jose", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Miguel", beneficiaries: "2,870", amount: "251,624" },
    { name: "San Remigio", beneficiaries: "1,045", amount: "207,135" },
    { name: "Santa Cruz", beneficiaries: "3,764", amount: "245,235" },
    { name: "Santo Niño", beneficiaries: "2,983", amount: "215,064" },
    { name: "Sion", beneficiaries: "1,870", amount: "211,233" },
    { name: "Tagbaobo", beneficiaries: "3,513", amount: "251,123" },
    { name: "Tagbay", beneficiaries: "2,124", amount: "152,724" },
    { name: "Tagbitan-ag", beneficiaries: "2,124", amount: "193,513" },
    { name: "Tagdaliao", beneficiaries: "1,972", amount: "212,523" },
    { name: "Tagpopongan", beneficiaries: "1,241", amount: "183,523" },
    { name: "Tambo", beneficiaries: "2,870", amount: "251,624" },
    { name: "Toril", beneficiaries: "2,870", amount: "251,624" },
]);
</script>
