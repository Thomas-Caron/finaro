<template>
    <div ref="chart"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import ApexCharts from 'apexcharts';

import useConvertFilter from '../../../composables/useConvertFilter';

const { getCurrency } = useConvertFilter();

const props = defineProps({
    chartData: { type: Array, required: true },
});

const chart = ref(null);
let chartInstance = null;

const buildOptions = (data) => ({
    series: [
        {
            name: "Capital remboursé",
            data: data.map(d => d.capital),
            color: "#38b2ac",
        },
        {
            name: "Intérêts payés",
            data: data.map(d => d.interest),
            color: "#9f7aea",
        },
        {
            name: "Assurance",
            data: data.map(d => d.insurance),
            color: "#e53e3e",
        },
    ],
    chart: {
        type: "bar",
        height: "100%",
        stacked: true,
        fontFamily: "Inter, sans-serif",
        dropShadow: { enabled: false },
        toolbar: { show: false },
        zoom: { enabled: false },
    },
    tooltip: {
        enabled: true,
        shared: false,
        custom: function({ series, seriesIndex, dataPointIndex }) {
            const d = data[dataPointIndex];
            return `<div class="rounded-lg w-64 py-4">
                        <div class="flex justify-between border-b border-stone-500 pb-2 px-4">
                            <span class="font-medium text-stone-700 dark:text-stone-300">${d.year} ${1 === d.year ? 'an' : 'ans'}</span>
                            <span class="font-medium text-stone-700 dark:text-stone-300">${getCurrency(d.insurance + d.interest + d.capital)}</span>
                        </div>
                        <div class="flex justify-between py-2 px-4">
                            <span class="text-stone-700 dark:text-stone-300">Assurance :</span>
                            <span class="font-medium text-red-500">${getCurrency(d.insurance)}</span>
                        </div>
                        <div class="flex justify-between py-2 px-4">
                            <span class="text-stone-700 dark:text-stone-300">Intérêts :</span>
                            <span class="font-medium text-purple-500">${getCurrency(d.interest)}</span>
                        </div>
                        <div class="flex justify-between px-4">
                            <span class="text-stone-700 dark:text-stone-300">Capital :</span>
                            <span class="font-medium text-teal-500">${getCurrency(d.capital)}</span>
                        </div>
                    </div>`;
        },
    },
    legend: { show: false },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: "70%",
        },
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
    },
    dataLabels: { enabled: false },
    stroke: { width: 1 },
    grid: {
        show: false,
        strokeDashArray: 4,
        padding: { left: 2, right: 2, top: 0 },
    },
    xaxis: {
        categories: data.map(d => d.year),
        labels: {
            formatter(value) {
                if (value === 1) return `${value} an`;
                if (value % 5 === 0 || value === Math.max(...data.map(d => d.year))) return `${value} ans`;
                return '';
            },
        },
        axisBorder: { show: false },
        axisTicks: { show: false },
    },
    yaxis: {
        labels: {
            formatter(value) {
                if (value >= 1_000_000) return (value / 1_000_000).toFixed(1).replace('.0', '') + ' M€';
                if (value >= 1_000) return (value / 1_000).toFixed(0) + ' K€';
                return value + ' €';
            },
        },
    },
});

const renderChart = async() => {
    if (!props.chartData) return;
    if (chartInstance) await chartInstance.destroy();

    chartInstance = new ApexCharts(chart.value, buildOptions(props.chartData));
    await chartInstance.render();
};

watch(() => props.chartData, () => {
    renderChart();
}, { deep: true });

onMounted(() => {
    renderChart();
});

onUnmounted(async () => {
    if (chartInstance) {
        await chartInstance.destroy();
    }
});
</script>