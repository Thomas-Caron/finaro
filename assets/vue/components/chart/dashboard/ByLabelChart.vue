<template>
    <div ref="chart"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import ApexCharts from 'apexcharts';

import useConvertFilter from '../../../composables/useConvertFilter.js';
import useTheme from '../../../composables/useTheme.js';

const { getCurrency } = useConvertFilter();
const { isDark } = useTheme();

const props = defineProps({
    chartData: {
        type: Object,
        required: true
    }
});

const chart = ref(null);
let chartInstance = null;

const buildOptions = (data) => ({
    series: data.series,
    labels: data.labels,
    chart: {
        height: "100%",
        width: "100%",
        type: "pie",
    },
    colors: data.colors,
    legend: {
        show: true,
        position: 'bottom',
        labels: {
            colors: isDark.value ? '#E5E5E5' : '#4B5563',
        }
    },
    tooltip: {
        enabled: true,
        y: {
            formatter: (value) => getCurrency(value)
        }
    },
    dataLabels: {
        enabled: true,
        formatter: (val) => `${Math.round(val)}%`,
        dropShadow: {
            enabled: false
        }
    },
    stroke: {
        show: true,
        colors: 'transparent',
    },
    plotOptions: {
        pie: {
            expandOnClick: true,
        }
    },
    states: {
        hover: { filter: { type: 'none' } },
        active: { filter: { type: 'none' } },
    }
});

const renderChart = async () => {
    if (!props.chartData || !props.chartData.series?.length) return;

    if (chartInstance) await chartInstance.destroy();

    chartInstance = new ApexCharts(chart.value, buildOptions(props.chartData));
    await chartInstance.render();
};

watch(() => props.chartData, () => {
    renderChart()
}, { deep: true });

watch(isDark, () => {
    renderChart()
});

onMounted(() => {
    renderChart()
});

onUnmounted(async () => {
    if (chartInstance) {
        await chartInstance.destroy();
    }
});
</script>