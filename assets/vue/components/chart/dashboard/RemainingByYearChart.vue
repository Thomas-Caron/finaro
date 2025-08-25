<template>
    <div ref="chart" class="w-full h-full"></div>
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
    series: [{
        name: 'Restant',
        data: data.series,
    }],
    chart: {
        type: 'bar',
        height: "100%",
        width: "100%",
        toolbar: { show: false }
    },
    xaxis: {
        categories: data.labels,
        labels: {
            style: {
                colors: isDark.value ? '#E5E5E5' : '#374151',
            }
        }
    },
    yaxis: {
        labels: {
            formatter: value => getCurrency(value),
            style: {
                colors: isDark.value ? '#E5E5E5' : '#374151',
            }
        }
    },
    plotOptions: {
        bar: {
            distributed: true,
            borderRadius: 4,
            borderRadiusApplication: 'end',
            horizontal: false,
            columnWidth: '90%',
        }
    },
    tooltip: {
        custom: function({series, seriesIndex, dataPointIndex, w}) {
            return `<div class="rounded-lg w-64 py-4">
                        <div class="flex justify-between border-b border-stone-500 pb-2 px-4">
                            <span class="font-medium text-stone-700 dark:text-stone-300">${w.globals.labels[dataPointIndex]}</span>
                        </div>
                        <div class="flex justify-between pt-2 px-4">
                            <span class="text-stone-700 dark:text-stone-300">Restant :</span>
                            <span class="terxt-bold text-stone-700 dark:text-stone-300">${getCurrency(series[seriesIndex][dataPointIndex])}</span>
                        </div>
                    </div>`;
        },
    },
    colors: data.series.map(val => val >= 0 ? '#95D5C1' : (isDark.value ? '#FCA5A5' : '#EF4444')),
    dataLabels: {
        enabled: true,
        formatter: val => getCurrency(val),
        style: {
            colors: [isDark.value ? '#E5E5E5' : '#374151']
        }
    },
    grid: {
        borderColor: isDark.value ? '#4B5563' : '#E5E7EB',
        strokeDashArray: 4
    },
    legend: { show: false },
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