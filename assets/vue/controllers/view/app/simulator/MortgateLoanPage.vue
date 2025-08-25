<template>
    <ContainerApp 
        :breadcrumbs="[
            { text: 'Simulateurs' },
            { text: 'Crédit immobilier', url: props.url.mortgateLoan }
        ]" 
    >
        <div class="flex flex-col gap-4">
            <div class="w-full rounded-lg shadow-sm p-4 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
                <p>Ce simulateur calcule le montant de vos mensualités et le coût total de votre crédit immobilier en fonction du montant emprunté, de la durée et du taux d’intérêt.</p>
                <p>Il vous aide à mieux planifier votre projet immobilier en visualisant l’impact des différentes conditions de prêt sur votre budget.</p>
            </div>

            <div class="flex flex-col md:flex-row gap-4 items-start">
                <div class="w-full md:w-1/3 rounded-lg shadow-sm bg-stone-50 dark:bg-stone-900 divide-y divide-stone-200 dark:divide-stone-700">
                    <form class="p-4" @submit.prevent="handleSubmit">
                        <Slider
                            id="amountBorrowed"
                            class="mb-3"
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
                            class="mb-3"
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
                            class="mb-3"
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
                    <p class="p-4 text-stone-500/50">
                        Cet outil sert uniquement à des fins d'information. Il ne doit pas être considéré comme un conseil financier.
                    </p>
                </div>

                <div class="w-full md:w-2/3 relative bg-stone-50 rounded-lg shadow-sm dark:bg-stone-900">
                    <Loader :loading="loading" class="rounded-lg" />
                    <div class="flex flex-col items-center p-4">
                        <div class="text-lg font-bold text-stone-700 dark:text-stone-100">Mensualités</div>
                        <div class="text-lg font-semibold text-stone-700 dark:text-stone-100">
                            <span v-if="data.monthlyPayment">{{ getCurrency(data.monthlyPayment) }}</span>
                        </div>
                        <div class="text-lg text-stone-700 dark:text-stone-100">
                            Dont assurance <span v-if="data.monthlyInsurance">{{ getCurrency(data.monthlyInsurance) }}</span>/mois
                        </div>
                    </div>

                    <div class="flex justify-between w-full max-w-md mx-auto px-4">
                        <div class="flex flex-col items-start text-sm text-stone-700 dark:text-stone-100">
                            <span>Coût total du crédit</span>
                            <span>Dont assurance</span>
                        </div>
                        <div class="flex flex-col items-end text-sm font-medium text-stone-700 dark:text-stone-100">
                            <span v-if="data.totalCreditCost">{{ getCurrency(data.totalCreditCost) }}</span>
                            <span v-if="data.totalInsurance">{{ getCurrency(data.totalInsurance) }}</span>
                        </div>
                    </div>

                    <MortgateLoanChart v-if="data.chartData.length" class="pt-4 pe-4 xl:pe-0 -mb-4 xl:mb-2" :chartData="data.chartData" />
                </div>
            </div>
        </div>
    </ContainerApp>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ContainerApp from '../../../../components/layout/container/ContainerApp.vue';
import Loader from '../../../../components/loader/Loader.vue';
import MortgateLoanChart from '../../../../components/chart/simulator/MortgateLoanChart.vue';
import Slider from '../../../../components/input/Slider.vue';

import useApi from '../../../../composables/useApi';
import useConvertFilter from '../../../../composables/useConvertFilter';

const { post } = useApi();
const { getCurrency } = useConvertFilter();

const props = defineProps({
    url: { type: Object, default: () => ({}) },
    api: { type: Object, default: () => ({}) }
});

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
});
</script>