<template>
    <ContainerApp
        :breadcrumbs="breadcrumbs"
        :api="api"
        :nav-date="true"
        :initialize="initialize"
        @update-date="handleUpdateDate"
    >
        <div class="grid gap-6">
            <div v-if="initialize && !loading" class="shadow-sm rounded-lg flex flex-col items-center justify-center p-4 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
                <p>Vous n'avez pas de compte courant pour le moment.</p>
                <p>Veuillez le créer pour commencer.</p>
                <ButtonLink
                    class="mt-4"
                    color="gradient"
                    :url="props.url.currentAccount"
                >
                    Créer mon compte courant
                </ButtonLink>
            </div>

            <EmptyDataMonthCard
                v-else-if="!monthExist && !loading" class="shadow-sm rounded-lg flex flex-col items-center justify-center p-4 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900"
                :api="props.api"
                :date="formDataDate"
            />

            <div v-else-if="!loading" class="shadow-sm rounded-lg relative text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
                <Loader :loading="loading" />
                <h3 class="text-lg font-semibold md:col-span-2 p-4 border-b border-stone-200 dark:border-stone-700"><a :href="props.url.currentAccount">Compte courant</a></h3>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-6 bg-stone-100/30 dark:bg-stone-800/30">
                    <h4 class="text-lg md:col-span-2">Pour l'année {{ formDataDate.year }}</h4>
                    <div class="min-h-65 md:col-span-2 shadow-sm rounded-lg flex items-center justify-center p-2 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
                        <RemainingByYearChart :chart-data="dataCalculatedRemainingByYear" />
                    </div>
                    <h4 class="text-lg md:col-span-2">Pour le mois {{ ['a','e','i','o','u','y'].includes(formDataDate.month.charAt(0)) ? 'd\'' : 'de ' }}{{ getStringMonth(formDataDate.month) }}</h4>
                    <div class="min-h-65 relative shadow-sm rounded-lg flex items-center justify-center p-2 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
                        <div class="absolute w-full h-full flex flex-col items-center justify-center">
                            <p class="text-sm text-stone-500 dark:text-stone-300">Solde restant estimé :</p>
                            <p 
                                :class="[
                                    {
                                        'text-stone-500 dark:text-stone-300': 0 < dataCalculatedRemaining.totalRemaining,
                                        'text-red-500 dark:text-red-300': 0 >= dataCalculatedRemaining.totalRemaining,
                                    },
                                    'text-sm font-semibold'
                                ]"
                            >
                                {{ getCurrency(dataCalculatedRemaining.totalRemaining) }}
                            </p>
                        </div>
                        <RemainingChart :chart-data="dataCalculatedRemaining" />
                    </div>

                    <Calendar
                        class="min-h-65"
                        :month="formDataDate?.month"
                        :year="formDataDate?.year"
                        :events="dataCalendar"
                    />

                    <div class="min-h-65 shadow-sm rounded-lg flex items-center justify-center p-2 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
                        <ByLabelChart :chart-data="dataCalculatedByLabel" />
                    </div>
                </div>
            </div>
        </div>
    </ContainerApp>
</template>

<script setup>
import { ref, computed, onMounted, provide } from 'vue';
import ButtonLink from '../../../components/button/ButtonLink.vue';
import ByLabelChart from '../../../components/chart/dashboard/ByLabelChart.vue';
import Calendar from '../../../components/calendar/Calendar.vue';
import ContainerApp from '../../../components/layout/container/ContainerApp.vue';
import EmptyDataMonthCard from '../../../components/card/EmptyDataMonthCard.vue';
import RemainingChart from '../../../components/chart/dashboard/RemainingChart.vue';
import RemainingByYearChart from '../../../components/chart/dashboard/RemainingByYearChart.vue';
import Loader from '../../../components/loader/Loader.vue';

import useApi from '../../../composables/useApi.js';
import useConvertFilter from '../../../composables/useConvertFilter.js';
import useCurrentDate from '../../../composables/useCurrentDate.js';
import useDateFormat from '../../../composables/useDateFormat.js';

const { get } = useApi();
const { getCurrency } = useConvertFilter();
const { dateNow } = useCurrentDate(); 
const { toFullDate, getStringMonth } = useDateFormat();

const props = defineProps({
    url: { type: Object, default: () => ({}) },
    api: { type: Object, default: () => ({}) }
});

const initialize = ref(true);
const loading = ref(true);
const dataCalendar = ref([]);
const dataCalculatedRemaining = ref({});
const dataCalculatedRemainingByYear = ref({});
const dataCalculatedByLabel = ref({});
const monthExist = ref(false);
const labels = ref([]);
const formDataDate = ref(dateNow);

provide('labels', labels);
provide('formDataDate', formDataDate);

const breadcrumbs = computed(() => {
    return [
        { text: 'Dashboard', url: props.url.dashboard },
        { text: `${getStringMonth(formDataDate?.value.month)} ${formDataDate?.value.year}` }
    ];
});

const handleUpdateDate = async (data) => {
    formDataDate.value = { month: data.month, year: data.year };

    if (initialize.value) {
        return;
    }

    await checkIfMonthExist();

    if (monthExist.value) {
        await getFixedExpenses();
        await getDataCalculatedRemaining();
        await getDataCalculatedRemainingByYear();
        await getDataCalculatedByLabel();
    }
};

const getAccount = async () => {
    const response = await get(props.api.getAccount);

    if (response.success) {
        initialize.value = response.initialize;
    }
};

const getLabels = async () => {
    try {
        const response = await get(props.api.getLabels);

        if (response.success) {
            labels.value = response.data.map(item => {
                return {
                    id: item.id,
                    name: item.name,
                    color: item.color,
                    icon: item.icon
                };
            });
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const getFixedExpenses = async () => {
    try {
        const response = await get(props.api.getFixedExpenses.replace('month', formDataDate.value.month).replace('year', formDataDate.value.year));

        if (response.success) {
            dataCalendar.value = response.data.map(item => {
                return {
                    title: item.name,
                    amount: getCurrency(item.amount),
                    date: toFullDate(item.chargedAt)
                };
            });
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const getDataCalculatedRemaining = async () => {
    try {
        const response = await get(props.api.getDataCalculatedRemaining.replace('month', formDataDate.value.month).replace('year', formDataDate.value.year));

        if (response.success) {
            dataCalculatedRemaining.value = response.data;
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const getDataCalculatedRemainingByYear = async () => {
    try {
        const response = await get(props.api.getDataCalculatedRemainingByYear.replace('year', formDataDate.value.year));

        if (response.success) {
            dataCalculatedRemainingByYear.value = response.data;
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const getDataCalculatedByLabel = async () => {
    try {
        const response = await get(props.api.getDataCalculatedByLabel.replace('month', formDataDate.value.month).replace('year', formDataDate.value.year));

        if (response.success) {
            dataCalculatedByLabel.value = response.data;
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const checkIfMonthExist = async () => {
    try {
        const response = await get(props.api.checkIfMonthExist.replace('month', formDataDate.value.month).replace('year', formDataDate.value.year));

        if (response.success) {
            monthExist.value = response.exist;
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

onMounted(async () => {
    await getAccount();

    if (!initialize.value) {
        await getLabels();
    }

    loading.value = false;
});
</script>