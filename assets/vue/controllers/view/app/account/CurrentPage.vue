<template>
    <Loader v-if="loading" :loading="loading" />
    <ContainerApp
        v-else
        class="relative"
        :breadcrumbs="breadcrumbs"
        :api="api"
        :nav-date="true"
        :initialize="initialize"
        :month-exist="monthExist"
        @update-date="handleUpdateDate"
    >

        <InitializeView
            v-if="initialize && !loading"
            :url="url"
            :api="api"
            :date="formDataDate"
        />

        <EmptyDataMonthCard
            v-else-if="!monthExist && !loading"
            :api="props.api"
        />

        <div v-else-if="!loading" class="">
            <div
                v-show="movementType === 'income'"
                class="grid gap-6"
            >
                <RemainingPrevious
                    :api="api"
                    :date="formDataDate"
                    @update="handleUpdate"
                />
                <Incomes
                    :api="api"
                    :date="formDataDate"
                    @update="handleUpdate"
                />
            </div>

            <div 
                v-show="movementType === 'expense'"
                class="grid grid-cols-1 md:grid-cols-2 gap-6"
            >
                <Expenses
                    :api="api"
                    :date="formDataDate"
                    @update="handleUpdate"
                />
                <FixedExpenses 
                    :api="api"
                    :date="formDataDate"
                    @update="handleUpdate"
                />
            </div>
        </div>
    </ContainerApp>
</template>

<script setup>
import { ref, computed, onMounted, provide } from 'vue';
import ContainerApp from '../../../../components/layout/container/ContainerApp.vue';
import EmptyDataMonthCard from '../../../../components/card/EmptyDataMonthCard.vue';
import Expenses from '../../../../components/view/app/account/current/sections/Expenses.vue';
import FixedExpenses from '../../../../components/view/app/account/current/sections/FixedExpenses.vue';
import Incomes from '../../../../components/view/app/account/current/sections/Incomes.vue';
import InitializeView from '../../../../components/view/app/account/current/InitializeView.vue';
import Loader from '../../../../components/loader/Loader.vue';
import RemainingPrevious from '../../../../components/view/app/account/current/sections/RemainingPrevious.vue';

import useApi from '../../../../composables/useApi.js';
import useCurrentDate from '../../../../composables/useCurrentDate.js';
import useDateFormat from '../../../../composables/useDateFormat.js';

const { get } = useApi();
const { dateNow } = useCurrentDate();
const { getStringMonth } = useDateFormat();

const props = defineProps({
    url: { type: Object, default: () => ({}) },
    api: { type: Object, default: () => ({}) }
});

const initialize = ref(true);
const loading = ref(true);
const monthExist = ref(false);
const labels = ref([]);
const formDataDate = ref(dateNow);
const movementType = ref('expense');
const dataCalculatedRemaining = ref({});

provide('labels', labels);
provide('formDataDate', formDataDate);
provide('movementType', movementType);
provide('dataCalculatedRemaining', dataCalculatedRemaining);

const breadcrumbs = computed(() => {
    return [
        { text: 'Comptes' },
        { text: 'Compte courant', url: props.url.current },
        { text: initialize.value ? 'Initialisation' : `${getStringMonth(formDataDate?.value.month)} ${formDataDate?.value.year}` }
    ];
});

const handleUpdateDate = async (data) => {
    formDataDate.value = { month: data.month, year: data.year };

    if (initialize.value) {
        return;
    }

    await checkIfMonthExist();

    if (monthExist.value) {
        await getDataCalculatedRemaining();
    }
};

const handleUpdate = async () => {
    await getDataCalculatedRemaining();
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
    await getLabels();

    loading.value = false;
});
</script>