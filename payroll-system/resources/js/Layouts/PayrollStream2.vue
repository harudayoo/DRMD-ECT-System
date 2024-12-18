<template>
    <div v-if="showGraph" class="mt-6">
      <div class="bg-white p-6 rounded-2xl shadow-md">
        <h4 class="text-xl font-semibold mb-4">Payroll Stream</h4>
        <div class="h-48">
          <canvas ref="chartCanvas"></canvas>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { onMounted, watch, ref, computed } from "vue";
  import Chart from "chart.js/auto";
  
  interface Barangay {
    barangayName: string;
    totalBeneficiaries: number;
    totalAmountReleased: number;
  }
  
  const props = defineProps<{
    showGraph: boolean;
    barangays: Barangay[];
  }>();
  
  const chartCanvas = ref<HTMLCanvasElement | null>(null);
  let chart: Chart | null = null;
  
  const labels = computed(() => 
    props.barangays.map((barangay) => barangay.barangayName)
  );
  
  const data = computed(() => ({
    labels: labels.value,
    datasets: [
      {
        label: "Number of Beneficiaries",
        backgroundColor: "rgb(59, 68, 122)",
        data: props.barangays.map((barangay) => barangay.totalBeneficiaries),
      },
      {
        label: "Amount Released",
        backgroundColor: "rgb(166, 170, 238)",
        data: props.barangays.map((barangay) => barangay.totalAmountReleased),
      },
    ],
  }));
  
  const createChart = () => {
    if (chartCanvas.value && props.barangays.length > 0) {
      chart = new Chart(chartCanvas.value, {
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
  
  const destroyChart = () => {
    if (chart) {
      chart.destroy();
      chart = null;
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
        createChart();
      } else {
        destroyChart();
      }
    }
  );
  
  watch(
    () => props.barangays,
    () => {
      if (chart) {
        destroyChart();
        createChart();
      }
    },
    { deep: true }
  );
  </script>