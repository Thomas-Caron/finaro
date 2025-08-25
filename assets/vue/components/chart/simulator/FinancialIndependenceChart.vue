<template>
    <div ref="chart"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import ApexCharts from 'apexcharts';

import useConvertFilter from '../../../composables/useConvertFilter';

const { getCurrency } = useConvertFilter();

const props = defineProps({
    chartData: { type: Object, required: true },
});

const chart = ref(null);
let chartInstance = null;

const buildOptions = (data) => ({
    series: [
        {
            name: "Patrimoine initial",
            data: data.initialHeritage || [],
            color: "#FF2056",
        },
        {
            name: "Versement",
            data: data.initialHeritage.map((value, index) => {
                return value + (data.deposits?.[index] || 0)
            }),
            color: "#38B2AC",
        },
        {
            name: "Intérêt",
            data: data.initialHeritage.map((value, index) => {
                return value + (data.deposits?.[index] || 0) + (data.interests?.[index] || 0)
            }),
            color: "#9F7AEA",
        },
    ],
    chart: {
        height: "100%",
        maxWidth: "100%",
        type: "area",
        fontFamily: "Inter, sans-serif",
        dropShadow: { enabled: false },
        toolbar: { show: false },
        zoom: { enabled: false },
    },
    tooltip: {
        enabled: true,
        shared: true,
        custom: function({ series, seriesIndex, dataPointIndex }) {
            const year = data.years[dataPointIndex];
            const initial = getCurrency(data.initialHeritage[dataPointIndex]);
            const deposit = getCurrency(data.deposits[dataPointIndex]);
            const interest = getCurrency(data.interests[dataPointIndex]);
            const total = getCurrency(data.total[dataPointIndex]);

            return `<div class="rounded-lg w-64 py-4">
                    <div class="flex justify-between border-b border-stone-500 pb-2 px-4">
                        <span class="font-medium text-stone-700 dark:text-stone-300">${year} ${year === 1 ? 'an' : 'ans'}</span>
                        <span class="font-medium text-stone-700 dark:text-stone-300">${total}</span>
                    </div>
                    <div class="flex justify-between pt-4 px-4">
                        <span class="text-stone-700 dark:text-stone-300">Intérêt :</span>
                        <span class="font-medium text-purple-500">${interest}</span>
                    </div>
                    <div class="flex justify-between py-2 px-4">
                        <span class="text-stone-700 dark:text-stone-300">Versement :</span>
                        <span class="font-medium text-teal-500">${deposit}</span>
                    </div>
                    <div class="flex justify-between px-4">
                        <span class="text-stone-700 dark:text-stone-300">Patrimoine initial :</span>
                        <span class="font-medium text-rose-500">${initial}</span>
                    </div>
                </div>`;
        },
    },
    legend: { show: false },
    fill: {
        type: "gradient",
        gradient: { opacityFrom: 0.75, opacityTo: 0.25, shade: "#1C64F2" },
    },
    dataLabels: { enabled: false },
    stroke: { 
        width: 3,
        curve: "monotoneCubic",
    },
    grid: {
        show: false,
        strokeDashArray: 4,
        padding: { left: 2, right: 2, top: 0 },
    },
    xaxis: {
        categories: data.years || [],
        labels: {
            formatter(value) {
                const maxYears = data.years.at(-1);
                if (value === 1) return value + " an";
                if (value % 5 === 0 || value === maxYears) return value + " ans";
                return "";
            },
        },
        axisBorder: { show: false },
        axisTicks: { show: false },
        tooltip: { enabled: false },
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

const renderChart = async () => {
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