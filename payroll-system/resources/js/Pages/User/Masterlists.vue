<template>
    <div>
        <h1>{{ barangay.barangayName }} Beneficiaries</h1>
        <table>
            <thead>
                <tr>
                    <th>Beneficiary Number</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Extension Name</th>
                    <th>Date of Birth</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="beneficiary in beneficiaries"
                    :key="beneficiary.beneficiaryID"
                >
                    <td>{{ beneficiary.beneficiaryNumber }}</td>
                    <td>{{ beneficiary.lastName }}</td>
                    <td>{{ beneficiary.firstName }}</td>
                    <td>{{ beneficiary.middleName }}</td>
                    <td>{{ beneficiary.extensionName }}</td>
                    <td>{{ beneficiary.dateOfBirth }}</td>
                    <td>{{ beneficiary.status }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    barangay: Object,
    beneficiaries: Array,
});
</script>
