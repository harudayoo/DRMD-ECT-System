<template>
    <div v-if="showGraph" class="mt-6">
        <div class="bg-white p-6 rounded-2xl shadow-md">
            <h4 class="text-xl font-semibold mb-4">Payroll Stream</h4>
            <div class="h-52">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, watch } from "vue";
import Chart from "chart.js/auto";

const props = defineProps<{
    showGraph: boolean;
}>();

let chart: Chart | null = null;

const labels = [
    "Asuncion",
    "Braulio E. Dujali",
    "Carmen",
    "Kapalong",
    "New Corella'",
    "Panabo",
    "Samal",
    "San Isidro",
    "Santo Tomas",
    "Tagum",
    "Talaingod",
];

const data = {
    labels: labels,
    datasets: [
        {
            label: "Number of Beneficiaries",
            backgroundColor: "rgb(59, 68, 122)",
            data: [
                350521, 303566, 207897, 227991, 351564, 986325, 238545, 264642,
                781348, 637586, 626488,
            ],
        },
        {
            label: "Amount Released",
            backgroundColor: "rgb(166, 170, 238)",
            data: [
                171624, 188943, 345234, 415853, 510427, 259873, 628714, 985524,
                639631, 678135, 259364,
            ],
        },
    ],
};

const createChart = () => {
    const ctx = document.getElementById("myChart") as HTMLCanvasElement;
    if (ctx) {
        chart = new Chart(ctx, {
            type: "bar",
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "bottom",
                    },
                },
            },
        });
    }
};

onMounted(() => {
    if (props.showGraph) {
        createChart();
    }
});

watch(
    () => props.showGraph,
    (newValue) => {
        if (newValue) {
            if (!chart) {
                createChart();
            }
        } else {
            if (chart) {
                chart.destroy();
                chart = null;
            }
        }
    }
);
</script>
