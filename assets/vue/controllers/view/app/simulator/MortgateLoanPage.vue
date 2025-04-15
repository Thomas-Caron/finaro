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
                        id="amountBorrowed"
                        lass="mb-3"
                        name="input_amount_borrowed"
                        label="Montant emprunté"
                        :min="0"
                        :max="1000000"
                        :step="10000"
                        unit="currency"
                        v-model="formData.amountBorrowed"
                        @change="handleSubmit"
                    />

                    <Slider
                        id="repaymentPeriod"
                        lass="mb-3"
                        name="input_repayment_period"
                        label="Durée de remboursement"
                        :min="1"
                        :max="30"
                        :step="1"
                        unit="year"
                        v-model="formData.repaymentPeriod"
                        @change="handleSubmit"
                    />

                    <Slider
                        id="interestRate"
                        lass="mb-3"
                        name="input_interest_rate"
                        label="Taux d'intérêt"
                        :min="0"
                        :max="10"
                        :step="0.1"
                        unit="percentage"
                        v-model="formData.interestRate"
                        @change="handleSubmit"
                    />

                    <Slider
                        id="insuranceRate"
                        lass="mb-3"
                        name="input_insurance_rate"
                        label="Taux d'asssurance"
                        :min="0"
                        :max="5"
                        :step="0.05"
                        unit="percentage"
                        v-model="formData.insuranceRate"
                        @change="handleSubmit"
                    />
                </form>
                <p class="border-t border-stone-200 dark:border-stone-700 pt-3 text-stone-500/40">
                    Cet outil sert uniquement à des fins d'information. Il ne doit pas être considéré comme un conseil financier.
                </p>
            </div>

            <div class="relative md:col-span-2 bg-stone-50 rounded-lg shadow-sm dark:bg-stone-900 p-4 md:p-6">
                <Loader :loading="loading" class="rounded-lg" />
                <div class="flex flex-col items-center">
                    <div class="text-lg font-bold text-stone-700 dark:text-stone-100">Mensualités</div>
                    <div class="text-lg font-semibold text-stone-700 dark:text-stone-100">
                        <span v-if="data.monthlyPayment">{{ getCurrency(data.monthlyPayment) }}</span>
                    </div>
                    <div class="text-lg text-stone-700 dark:text-stone-100">
                        Dont assurance <span v-if="data.monthlyInsurance">{{ getCurrency(data.monthlyInsurance) }}</span>/mois
                    </div>
                </div>

                <div class="flex justify-between w-full max-w-md mx-auto mt-2">
                    <div class="flex flex-col items-start text-sm text-stone-700 dark:text-stone-100">
                        <span>Coût total du crédit</span>
                        <span>Dont assurance</span>
                    </div>
                    <div class="flex flex-col items-end text-sm font-medium text-stone-700 dark:text-stone-100">
                        <span v-if="data.totalCreditCost">{{ getCurrency(data.totalCreditCost) }}</span>
                        <span v-if="data.totalInsurance">{{ getCurrency(data.totalInsurance) }}</span>
                    </div>
                </div>

                <MortgateLoanChart v-if="data.chartData.length" :chartData="data.chartData" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import Loader from '../../../../components/loader/Loader.vue';
import MortgateLoanChart from '../../../../components/chart/MortgateLoanChart.vue';
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
    amountBorrowed: 50000,
    repaymentPeriod: 20,
    interestRate: 3.5,
    insuranceRate: 0.15
});

const data = ref({
    monthlyPayment: 0,
    monthlyInsurance: 0,
    totalCreditCost: 0,
    totalInsurance: 0,
    totalAmount: 0,
    chartData: {}
});

const loading = ref(false);

const handleSubmit = async () => {
    try {
        loading.value = true;
        const response = await post(props.api.calculateMortgateLoan, formData.value);

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
        { text: 'Crédit immobilier', url: props.url.mortgateLoan },
    ]);
});

onUnmounted(() => {
    breadcrumb.clear()
});
</script>