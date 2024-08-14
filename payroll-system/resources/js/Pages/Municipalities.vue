<template>
    <div>
        <h1>Municipalities for Province ID: {{ provinceID }}</h1>
        <table>
            <thead>
                <tr>
                    <th>Municipality Name</th>
                    <th>Total Beneficiaries</th>
                    <th>Total Amount Released</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="municipality in municipalities"
                    :key="municipality.id"
                >
                    <td>{{ municipality.municipalityName }}</td>
                    <td>{{ municipality.totalBeneficiaries }}</td>
                    <td>
                        ₱{{
                            parseFloat(
                                municipality.totalAmountReleased
                            ).toFixed(2)
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["municipalities", "provinceID"],
    setup(props) {
        return {
            municipalities: props.municipalities,
            provinceID: props.provinceID,
        };
    },
};
</script>
