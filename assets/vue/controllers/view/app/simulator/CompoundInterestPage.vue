<template>
    <div
        :class="[
            {
                'ml-20': sidebar.isCollapsed,
                'ml-64': !sidebar.isCollapsed
            },
            'px-6 mb-6'
        ]"
    >
        <div class="grid grid-rows-2 grid-cols-1 md:grid-rows-1 md:grid-cols-3 gap-4">
            <div class="rounded-lg shadow-sm bg-stone-50 dark:bg-stone-900 p-4 md:p-6 flex flex-col justify-between">
                <form @submit.prevent="handleSubmit">
                    <Slider
                        id="initialCapital"
                        lass="mb-3"
                        name="input_initial_capital"
                        label="Capital initial"
                        :min="0"
                        :max="1000000"
                        :step="100"
                        unit="currency"
                        v-model="formData.initialCapital"
                        @change="handleSubmit"
                    />

                    <Slider
                        id="monthlySavings"
                        lass="mb-3"
                        name="input_monthly_savings"
                        label="Épargne mensuelle"
                        :min="0"
                        :max="10000"
                        :step="50"
                        unit="currency"
                        v-model="formData.monthlySavings"
                        @change="handleSubmit"
                    />

                    <Slider
                        id="investmentHorizon"
                        lass="mb-3"
                        name="input_investment_horizon"
                        label="Horizon de placement"
                        :min="1"
                        :max="50"
                        :step="1"
                        unit="year"
                        v-model="formData.investmentHorizon"
                        @change="handleSubmit"
                    />

                    <Slider
                        id="interestRate"
                        lass="mb-3"
                        name="input_interest_rate"
                        label="Taux d'intérêt"
                        :min="0"
                        :max="20"
                        :step="0.1"
                        unit="percentage"
                        v-model="formData.interestRate"
                        @change="handleSubmit"
                    />

                    <Slider
                        id="interestPaymentInterval"
                        lass="mb-3"
                        name="input_interest_payment_interval"
                        label="Intervalle de versement des intérêts"
                        :min="1"
                        :max="12"
                        :step="1"
                        unit="month"
                        v-model="formData.interestPaymentInterval"
                        @change="handleSubmit"
                    />
                </form>

                <p class="border-t border-stone-200 dark:border-stone-700 pt-3 text-stone-500/40">
                    Cet outil sert uniquement à des fins d'information. Il ne doit pas être considéré comme un conseil financier.
                </p>
            </div>

            <div class="relative md:col-span-2 rounded-lg shadow-sm bg-stone-50 dark:bg-stone-900 p-4 md:p-6">
                <Loader :loading="loading" class="rounded-lg" />
                <div class="flex flex-col items-center">
                    <div class="text-lg font-bold text-stone-700 dark:text-stone-100">Capital final</div>
                    <div class="text-lg font-semibold text-stone-700 dark:text-stone-100">
                        <span v-if="data.capital.length">{{ getCurrency(data.capital[data.capital.length - 1]) }}</span>
                    </div>
                </div>

                <div class="flex justify-between w-full max-w-md mx-auto mt-2">
                    <div class="flex flex-col items-start text-sm text-stone-700 dark:text-stone-100">
                        <span>Intérêts</span>
                        <span>Versements</span>
                    </div>
                    <div class="flex flex-col items-end text-sm font-medium text-stone-700 dark:text-stone-100">
                        <span v-if="data.interest.length">{{ getCurrency(data.interest[data.interest.length - 1]) }}</span>
                        <span v-if="data.saving.length">{{ getCurrency(data.saving[data.saving.length - 1]) }}</span>
                    </div>
                </div>

                <CompoundInterestChart v-if="data" :chartData="data" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import Loader from '../../../../components/loader/Loader.vue';
import CompoundInterestChart from '../../../../components/chart/CompoundInterestChart.vue';
import Slider from '../../../../components/input/Slider.vue';

import { useBreadcrumbStore } from '../../../../stores/useBreadcrumbStore';
import { useSidebarStore } from '../../../../stores/useSidebarStore';
import useApi from '../../../../composables/useApi';
import useConvertFilter from '../../../../composables/useConvertFilter';

const { post } = useApi();
const { getCurrency } = useConvertFilter();

const props = defineProps({
    url: {
        type: Object,
        default: () => ({})
    },
    api: {
        type: Object,
        default: () => ({})
    }
});

const breadcrumb = useBreadcrumbStore();
const sidebar = useSidebarStore();

const formData = ref({
    initialCapital: 10000,
    monthlySavings: 100,
    investmentHorizon: 20,
    interestRate: 7,
    interestPaymentInterval: 12
});

const data = ref({
    capital: [],
    interest: [],
    saving: [],
    years: []
});

const loading = ref(false);

const handleSubmit = async () => {
    try {
        loading.value = true;
        const response = await post(props.api.calculateCompoundInterest, formData.value);

        if (response.success) {
            loading.value = false;
            data.value = response.data;
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

onMounted(() => {
    handleSubmit();
    breadcrumb.set([
        { text: 'Simulateurs' },
        { text: 'Intérêts composés', url: props.url.compoundInterest },
    ]);
});

onUnmounted(() => {
    breadcrumb.clear()
});
</script>