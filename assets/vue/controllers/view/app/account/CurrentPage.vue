<template>
    <ContainerApp
        :breadcrumbs="breadcrumbs"
    >
        <InitializeView v-if="initialize" :url="url" :api="api" :date-now="dateNow" />

        <div v-else>
            <!-- <form v-if="dateData?.months?.length && dateData?.years?.length" class="flex flex-row justify-start mb-6">
                <Select 
                    id="selectMonth"
                    name="select_month"
                    class="me-4"
                    :items="dateData.months"
                    v-model="formFilterData.month"
                />
                <Select 
                    id="selectYear"
                    name="select_year"
                    :items="dateData.years"
                    v-model="formFilterData.year"
                />
            </form> -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-6">
                <div class="mb-6 md:mb-0">
                    <RemainingPrevious
                        class="mb-6"
                        :api="api"
                        :date="formFilterData"
                    />
                    <Incomes class="mb-6"
                        :api="api"
                        :date="formFilterData"
                    />
                    <FixedExpenses 
                        :api="api"
                        :date="formFilterData"
                    />
                </div>

                <div>
                    <Expenses
                        :api="api"
                        :date="formFilterData"
                    />
                </div>
            </div>
        </div>
    </ContainerApp>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue';
import ContainerApp from '../../../../components/layout/container/ContainerApp.vue';
import Expenses from '../../../../components/view/app/account/current/sections/Expenses.vue';
import FixedExpenses from '../../../../components/view/app/account/current/sections/FixedExpenses.vue';
import Incomes from '../../../../components/view/app/account/current/sections/Incomes.vue';
import InitializeView from '../../../../components/view/app/account/current/InitializeView.vue';
import Select from '../../../../components/input/Select.vue';
import RemainingPrevious from '../../../../components/view/app/account/current/sections/RemainingPrevious.vue';

import useApi from '../../../../composables/useApi.js';
import useCurrentDate from '../../../../composables/useCurrentDate.js';

const { get } = useApi();
const { dateNow } = useCurrentDate();

const props = defineProps({
    url: { type: Object, default: () => ({}) },
    api: { type: Object, default: () => ({}) }
});

const initialize = ref(false);

const dateData = ref({
    years: [],
    months: []
});

const formFilterData = reactive({
    month: dateNow.month,
    year: dateNow.year,
});

const breadcrumbs = computed(() => {
    const selectedMonth = dateData.value.months.find(item => item.value === formFilterData.month)?.label || '';
    const selectedYear = dateData.value.years.find(item => item.value === parseInt(formFilterData.year))?.label || '';
    
    return [
        { text: 'Comptes' },
        { text: 'Compte courant', url: props.url.current },
        { text: initialize.value ? 'Initialisation' : `${selectedMonth} ${selectedYear}` }
    ];
});

const getAccount = async () => {
    const response = await get(props.api.getAccount);

    if (response.success) {
        initialize.value = response.initialize;
    }
};

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

onMounted(async () => {
    await getAccount();
    if (initialize.value) {
        return;
    }

    await getMonths();
    await getYears();
});
</script>