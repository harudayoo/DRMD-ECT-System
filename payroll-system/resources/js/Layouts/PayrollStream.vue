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
import { onMounted, watch, computed } from "vue";
import Chart from "chart.js/auto";

const props = defineProps<{
    showGraph: boolean;
    provinces: Array<{
        provinceName: string;
        totalBeneficiaries: number;
        totalAmountReleased: string;
    }>;
}>();

let chart: Chart | null = null;

const labels = computed(() =>
    props.provinces.map((province) => province.provinceName)
);

const data = computed(() => ({
    labels: labels.value,
    datasets: [
        {
            label: "Number of Beneficiaries",
            backgroundColor: "rgb(59, 68, 122)",
            data: props.provinces.map(
                (province) => province.totalBeneficiaries
            ),
        },
        {
            label: "Amount Released",
            backgroundColor: "rgb(166, 170, 238)",
            data: props.provinces.map((province) =>
                parseFloat(province.totalAmountReleased)
            ),
        },
    ],
}));

const createChart = () => {
    const ctx = document.getElementById("myChart") as HTMLCanvasElement;
    if (ctx) {
        chart = new Chart(ctx, {
            type: "bar",
            data: data.value,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
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

watch(
    () => props.provinces,
    () => {
        if (chart) {
            chart.data = data.value;
            chart.update();
        }
    },
    { deep: true }
);
</script>
