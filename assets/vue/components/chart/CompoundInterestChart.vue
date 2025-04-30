<template>
    <div ref="chart"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import ApexCharts from 'apexcharts';

const props = defineProps({
    chartData: { type: Object, required: true },
});

const chart = ref(null);
let chartInstance = null;

const buildOptions = (data) => ({
    series: [
        {
            name: "Versement",
            data: data.capital || [],
            color: "#38B2AC"
        },
        {
            name: "Intérêts",
            data: data.saving || [],
            color: "#9F7AEA"
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
            const capital = data.capital[dataPointIndex]?.toLocaleString("fr-FR") + ' €';
            const saving = data.saving[dataPointIndex]?.toLocaleString("fr-FR") + ' €';
            const interest = data.interest[dataPointIndex]?.toLocaleString("fr-FR") + ' €';

            return `<div class="rounded-lg w-64 py-4">
                        <div class="flex justify-between border-b border-stone-500 pb-2 px-4">
                            <span class="font-medium text-stone-700 dark:text-stone-300">${year} ${year === 1 ? 'an' : 'ans'}</span>
                            <span class="font-medium text-stone-700 dark:text-stone-300">${capital}</span>
                        </div>
                        <div class="flex justify-between py-2 px-4">
                            <span class="text-stone-700 dark:text-stone-300">Intérêts :</span>
                            <span class="font-medium text-teal-500">${interest}</span>
                        </div>
                        <div class="flex justify-between px-4">
                            <span class="text-stone-700 dark:text-stone-300">Versement :</span>
                            <span class="font-medium text-purple-500">${saving}</span>
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