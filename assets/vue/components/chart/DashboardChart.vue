<template>
    <div ref="chart"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import ApexCharts from 'apexcharts';

import useTheme from '../../composables/useTheme.js';

const { isDark } = useTheme();

const props = defineProps({
    chartData: { type: Object, required: true },
});

const chart = ref(null);
let chartInstance = null;

const buildOptions = (data) => ({
    series: [data.totalRemainingPercent, data.totalSpentPercent],
    chart: {
        height: "100%",
        maxWidth: "100%",
        type: "donut",
    },
    colors: ['#95D5C1', isDark.value ? '#57534D' : '#D6D3D1'],
    legend: {
        show: false,
    },
    tooltip: {
        enabled: false,
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        show: true,
        colors: 'transparent',
    },
    plotOptions: {
        pie: {
            expandOnClick: false
        }
    },
    states: {
        hover: {
            filter: {
                type: 'none',
            },
        },
        active: {
            filter: {
                type: 'none',
            },
        },
    }    
});

const renderChart = async () => {
    if (
        !props.chartData ||
        typeof props.chartData.totalRemainingPercent !== 'number' ||
        typeof props.chartData.totalSpentPercent !== 'number'
    ) {
        return;
    }

    if (chartInstance) await chartInstance.destroy();

    chartInstance = new ApexCharts(chart.value, buildOptions(props.chartData));
    await chartInstance.render();
};

watch(() => props.chartData, () => {
    renderChart();
}, { deep: true });

watch(isDark, () => {
    renderChart();
});

onMounted(() => {
    renderChart();
});

onUnmounted(async () => {
    if (chartInstance) {
        await chartInstance.destroy();
    }
});
</script>