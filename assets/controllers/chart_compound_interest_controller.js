import { Controller } from '@hotwired/stimulus';
import ApexCharts from 'apexcharts';

export default class extends Controller {
    static targets = ["chart"];
    static values = { chart: Object };

    connect() {
        setTimeout(() => {
            if (this.hasChartTarget && typeof ApexCharts !== 'undefined') {
                this.initChart();
            }
        }, 500);

        document.addEventListener("slider:update", () => this.updateChart());
    }

    disconnect() {
        document.removeEventListener("slider:update", () => this.updateChart());
    }

    initChart() {
        this.chart = new ApexCharts(this.chartTarget, this.getChartOptions());
        this.chart.render();
    }

    updateChart() {
        setTimeout(() => {
            if (this.chart && this.chartValue) {
                this.chart.destroy();
                this.initChart();
            }
        }, 500);
    }

    getChartOptions() {
        let parsedData = {};

        const chartData = this.chartTarget.getAttribute("data-chart-value");

        if (0 < chartData.length) {
            parsedData = JSON.parse(chartData.replace(/&quot;/g, '"'));

            return {
                series: [
                    {
                        name: "Versement",
                        data: parsedData.capital || [],
                        color: "#38b2ac",
                    },
                    {
                        name: "Intérêts",
                        data: parsedData.saving || [],
                        color: "#9f7aea",
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
                    custom: function({ series, seriesIndex, dataPointIndex, w }) {
                        const year = parsedData.years[dataPointIndex];
                        const capital = parsedData.capital[dataPointIndex].toLocaleString("fr-FR") + ' €';
                        const saving = parsedData.saving[dataPointIndex].toLocaleString("fr-FR") + ' €';
                        const interest = parsedData.interest[dataPointIndex].toLocaleString("fr-FR") + ' €';
                
                        return `<div class="rounded-lg w-64 py-4">
                                    <div class="flex justify-between border-b border-stone-500 pb-2 px-4">
                                        <span class="font-medium text-stone-700 dark:text-stone-300">${year} ${1 == year ? 'an' : 'ans'}</span>
                                        <span class="font-medium text-stone-700 dark:text-stone-300">${capital}</span>
                                    </div>

                                    <div class="flex justify-between py-2 px-4">
                                        <span class="text-stone-700 dark:text-stone-300"">Intérêts :</span>
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
                    categories: parsedData.years || [],
                    labels: {
                        formatter: function (value) {
                            const maxYears = parsedData.years[parsedData.years.length - 1];
                
                            if (value === 1) {
                                return value + " an";
                            } else if (value === 1 || value % 5 === 0 || value === maxYears) {
                                return value + " ans";
                            } else {
                                return "";
                            }
                        },
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    tooltip: { enabled: false },
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1).replace('.0', '') + ' M€';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + ' K€';
                            } else {
                                return value + ' €';
                            }
                        },
                    },
                },
            };
        }
    }
}