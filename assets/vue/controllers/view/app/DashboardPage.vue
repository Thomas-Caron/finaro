<template>
    <ContainerApp
        :breadcrumbs="breadcrumbs"
    >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-6">
            <div class="min-h-60 relative shadow-sm rounded-lg flex items-center justify-center p-2 mb-6 md:mb-0 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
                <Loader :loading="loading" />
                <div class="absolute w-full h-full flex flex-col items-center justify-center">
                    <p class="text-sm text-stone-500 dark:text-stone-300">Solde restant estimé :</p>
                    <p 
                        :class="[
                            {
                                'text-stone-500 dark:text-stone-300': 0 < dataCalculated.totalRemaining,
                                'text-red-500 dark:text-red-300': 0 >= dataCalculated.totalRemaining,
                            },
                            'text-sm font-semibold'
                        ]"
                    >
                        {{ getCurrency(dataCalculated.totalRemaining) }}
                    </p>
                </div>
                <DashboardChart :chart-data="dataCalculated" />
            </div>
            <Calendar
                :month="formFilterData.month"
                :year="formFilterData.year"
                :events="dataCalendar"
            />
        </div>
    </ContainerApp>
</template>

<script setup>
import { ref, computed, reactive, onMounted } from 'vue';
import Calendar from '../../../components/calendar/Calendar.vue';
import ContainerApp from '../../../components/layout/container/ContainerApp.vue';
import DashboardChart from '../../../components/chart/DashboardChart.vue';
import Loader from '../../../components/loader/Loader.vue';

import useApi from '../../../composables/useApi.js';
import useConvertFilter from '../../../composables/useConvertFilter.js';
import useCurrentDate from '../../../composables/useCurrentDate.js';
import useDateFormat from '../../../composables/useDateFormat.js';

const { get } = useApi();
const { getCurrency } = useConvertFilter();
const { dateNow } = useCurrentDate();
const { toFullDate } = useDateFormat();

const props = defineProps({
    url: { type: Object, default: () => ({}) },
    api: { type: Object, default: () => ({}) }
});

const dateData = ref({
    years: [],
    months: []
});

const formFilterData = reactive({
    month: dateNow.month,
    year: dateNow.year,
});

const loading = ref(false);
const dataCalendar = ref([]);
const dataCalculated = ref({});

const breadcrumbs = computed(() => {
    const selectedMonth = dateData.value.months.find(item => item.value === formFilterData.month)?.label || '';
    const selectedYear = dateData.value.years.find(item => item.value === parseInt(formFilterData.year))?.label || '';
    
    return [
        { text: 'Dashboard', url: props.url.dashboard },
        { text: `${selectedMonth} ${selectedYear}` }
    ];
});

const getMonths = async () => {
    const response = await get(props.api.getMonths);

    if (response.success) {
        dateData.value.months = response.data;

        const foundMonth = dateData.value.years.find(item => item.value === dateNow.month);
        if (foundMonth) {
            formFilterData.month = foundMonth.value;
        }
    }
};

const getYears = async () => {
    const response = await get(props.api.getYears);

    if (response.success) {
        dateData.value.years = response.data;

        const foundYear = dateData.value.years.find(item => item.value === dateNow.year);
        if (foundYear) {
            formFilterData.year = foundYear.value;
        }
    }
};

const getFixedExpenses = async () => {
    try {
        const response = await get(props.api.getFixedExpenses.replace('month', formFilterData.month).replace('year', formFilterData.year));

        if (response.success) {
            dataCalendar.value = response.data.map(item => {
                return {
                    title: item.name,
                    amount: getCurrency(item.amount),
                    date: toFullDate(item.prelevedAt)
                };
            });
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const getDataCalculated = async () => {
    try {
        loading.value = true;
        const response = await get(props.api.getDataCalculated.replace('month', formFilterData.month).replace('year', formFilterData.year));

        if (response.success) {
            loading.value = false;
            dataCalculated.value = response.data;
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

onMounted(async () => {
    await getMonths();
    await getYears();
    await getFixedExpenses();
    await getDataCalculated();
});
</script>