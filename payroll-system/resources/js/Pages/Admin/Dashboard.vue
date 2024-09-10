<template>
    <NSAdmin>
        <template #default>
            <div class="container mx-auto h-full px-6 py-8">
                <h3 class="text-gray-700 text-2xl font-medium">
                    Admin Dashboard
                </h3>
                <!-- Chart area -->
                <div class="w-full h-[95%] px-14">
                    <canvas ref="myChart"></canvas>
                </div>
            </div>
        </template>
    </NSAdmin>
</template>

<script>
import { ref, onMounted, watch } from "vue";
import Chart from "chart.js/auto";
import NSAdmin from "@/Layouts/NSAdmin.vue";

export default {
    name: "Dashboard",
    components: {
        NSAdmin, // Register the NSAdmin component
    },
    props: {
        userData: {
            type: Array,
            default: () => [],
        },
    },
    setup(props) {
        const myChart = ref(null);
        const chart = ref(null);

        const updateChart = () => {
            if (chart.value) {
                chart.value.data.labels = props.userData.map(
                    (user) => user.name
                );
                chart.value.data.datasets[0].data = props.userData.map(
                    (user) => user.loginNum
                );
                chart.value.update();
            }
        };

        const initChart = () => {
            if (myChart.value && props.userData.length > 0) {
                const ctx = myChart.value.getContext("2d");
                chart.value = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: props.userData.map((user) => user.name),
                        datasets: [
                            {
                                label: "Number of Logins",
                                backgroundColor: "rgb(0, 76, 153)",
                                borderColor: "rgb(255, 102, 178)",
                                data: props.userData.map(
                                    (user) => user.loginNum
                                ),
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: "Number of Logins",
                                },
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: "Users",
                                },
                            },
                        },
                    },
                });
            } else if (myChart.value) {
                const ctx = myChart.value.getContext("2d");
                ctx.font = "20px Arial";
                ctx.fillText("No user data available", 10, 50);
            }
        };

        onMounted(() => {
            initChart();
        });

        watch(
            () => props.userData,
            () => {
                if (chart.value) {
                    chart.value.destroy();
                }
                initChart();
            },
            { deep: true }
        );

        return {
            myChart,
        };
    },
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");

.font-inter {
    font-family: "Inter", sans-serif;
}
</style>
