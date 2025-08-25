<template>
    <ContainerApp 
        :breadcrumbs="[
            { text: 'Simulateurs' },
            { text: 'Indépendance financière', url: props.url.financialIndependence }
        ]"
    >
        <div class="flex flex-col gap-4">
            <div class="w-full rounded-lg shadow-sm p-4 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
                <p>Ce simulateur estime combien de revenu passif mensuel vous pouvez obtenir à partir d’un capital investi sur plusieurs années.</p>
                <p>Projection réaliste, utile pour préparer une retraite anticipée ou viser l’indépendance financière.</p>
            </div>

            <div class="w-full flex flex-col md:flex-row gap-4 items-start">
                <div class="md:w-1/3 rounded-lg shadow-sm bg-stone-50 dark:bg-stone-900 divide-y divide-stone-200 dark:divide-stone-700">
                    <form class="divide-y divide-stone-200 dark:divide-stone-700" @submit.prevent="handleSubmit">
                        <div class="p-4">
                            <h3 class="text-stone-900 dark:text-stone-50 font-semibold">Patrimoine</h3>
                            <Slider
                                id="currentHeritage"
                                class="mb-3"
                                name="current_heritage"
                                label="Patrimoine actuel"
                                :min="0"
                                :max="1000000"
                                :step="100"
                                unit="currency"
                                v-model="formData.currentHeritage"
                                @change="handleSubmit"
                            />
                            <DualSlider
                                id="initialAllocation"
                                name="initial_allocation"
                                label="Répartition du patrimoine initial"
                                label-left="Bourse"
                                label-right="Autre"
                                :min="0"
                                :max="100"
                                :step="1"
                                unit="percentage"
                                v-model="formData.initialAllocation"
                                @change="handleSubmit"
                            />
                        </div>

                        <div class="p-4">
                            <h3 class="text-stone-900 dark:text-stone-50 font-semibold">Investissement</h3>
                            <Slider
                                id="monthInvestment"
                                class="mb-3"
                                name="month_investment"
                                label="Investissement par mois"
                                :min="0"
                                :max="50000"
                                :step="100"
                                unit="currency"
                                v-model="formData.monthInvestment"
                                @change="handleSubmit"
                            />
                            <DualSlider
                                id="investmentAllocation"
                                name="investment_allocation"
                                label="Répartition des investissements"
                                label-left="Bourse"
                                label-right="Autre"
                                :min="0"
                                :max="100"
                                :step="1"
                                unit="percentage"
                                v-model="formData.investmentAllocation"
                                @change="handleSubmit"
                            />
                        </div>

                        <div class="p-4">
                            <h3 class="text-stone-900 dark:text-stone-50 font-semibold">Paramètres</h3>
                            <Slider
                                id="savingYears"
                                class="mb-3"
                                name="saving_years"
                                label="Nombre d’années d’épargne"
                                :min="1"
                                :max="100"
                                :step="1"
                                unit="year"
                                v-model="formData.savingYears"
                                @change="handleSubmit"
                            />
                            <Slider
                                id="withdrawalRate"
                                name="withdrawal_rate"
                                label="Taux de retrait"
                                :min="1"
                                :max="10"
                                :step="1"
                                unit="percentage"
                                v-model="formData.withdrawalRate"
                                @change="handleSubmit"
                            />
                        </div>

                        <div class="p-4">
                            <h3 class="text-stone-900 dark:text-stone-50 font-semibold">Rendements</h3>
                            <Slider
                                id="stockYield"
                                class="mb-3"
                                name="stock_yield"
                                label="Rendement bourse"
                                :min="1"
                                :max="20"
                                :step="1"
                                unit="percentage"
                                v-model="formData.stockYield"
                                @change="handleSubmit"
                            />
                            <Slider
                                id="otherYield"
                                name="other_yield"
                                label="Rendement autre"
                                :min="1"
                                :max="20"
                                :step="1"
                                unit="percentage"
                                v-model="formData.otherYield"
                                @change="handleSubmit"
                            />
                        </div>

                        <div class="p-4">
                            <h3 class="text-stone-900 dark:text-stone-50 font-semibold">Fiscalité</h3>
                            <button
                                type="button"
                                :class="[
                                    { 'mb-3': formData.withTax },
                                    'w-full flex justify-end text-sm text-stone-600 dark:text-stone-400 cursor-pointer hover:underline'
                                ]"
                                @click="() => { formData.withTax = !formData.withTax; handleSubmit(); }"
                            >
                                <span class="me-2">{{ formData.withTax ? 'Masquer la fiscalité' : 'Afficher la fiscalité' }}</span>
                                <Icon v-if="formData.withTax" class="size-5" name="ChevronDown" />
                                <Icon v-else class="size-5" name="ChevronRight" />
                            </button>
                            <Slider
                                v-if="formData.withTax"
                                id="inflationRate"
                                class="mb-3"
                                name="inflation_rate"
                                label="Taux d’inflation"
                                :min="1"
                                :max="10"
                                :step="1"
                                unit="percentage"
                                v-model="formData.inflationRate"
                                @change="handleSubmit"
                            />
                            <Input
                                v-if="formData.withTax"
                                id="stockTax"
                                class="mb-3"
                                name="stock_tax"
                                label="Taux d’imposition bourse"
                                :icon="{ name: 'Percent', direction: 'right' }"
                                :disabled="true"
                                v-model="formData.stockTax"
                            />
                            <Input
                                v-if="formData.withTax"
                                id="otherTax"
                                name="other_tax"
                                label="Taux d’imposition autre"
                                :icon="{ name: 'Percent', direction: 'right' }"
                                :disabled="true"
                                v-model="formData.otherTax"
                            />
                        </div>
                    </form>

                    <p class="p-4 text-stone-500/50">
                        Cet outil sert uniquement à des fins d'information. Il ne doit pas être considéré comme un conseil financier.
                    </p>
                </div>

                <div class="w-full md:w-2/3 relative bg-stone-50 rounded-lg shadow-sm dark:bg-stone-900">
                    <Loader :loading="loading" class="rounded-lg" />
                    <div class="text-lg text-center text-stone-700 dark:text-stone-100 p-4">
                        Au bout de {{ data.years[data.years.length - 1]  }} {{ 1 === data.years[data.years.length - 1] ? 'an' : 'ans' }}, vous pouvez générer un revenu passif de <span class="font-semibold">{{ getCurrency(data.safeWithdrawal) }}/mois</span> pour un capital final de <span class="font-semibold">{{ getCurrency(data.total[data.total.length - 1]) }}</span>
                    </div>

                    <FinancialIndependenceChart v-if="data" class="pt-4 pe-4 xl:pe-0 -mb-4 xl:mb-2" :chartData="data" />

                    <div class="text-center text-stone-700 dark:text-stone-100 p-4">
                        Celui-ci se compose de <span class="font-semibold">{{ getCurrency(data.deposits[data.deposits.length - 1]) }}</span> de versements et de <span class="font-semibold">{{ getCurrency(data.interests[data.interests.length - 1]) }}</span> d’intérêts perçus pour un revenu mensuel de <span class="font-semibold">{{ getCurrency(data.safeWithdrawal) }}</span> à partir de la {{ data.years[data.years.length - 1] }}{{ 1 === data.years[data.years.length - 1] ? 'ère' : 'ème' }} année.
                    </div>

                    <div class="grid grid-rows-1 grid-cols-3 gap-4 p-4">
                        <div class="text-stone-700 dark:text-stone-100 flex flex-col items-center">
                            <p>Plus-value</p>
                            <p class="font-semibold">{{ getCurrency(data.interests[data.interests.length - 1]) }}</p>
                        </div>
                        <div class="text-stone-700 dark:text-stone-100 flex flex-col items-center">
                            <p>Valeur {{ formData.withTax ? 'nette' : 'brut' }}</p>
                            <p class="font-semibold">{{ getCurrency(data.total[data.total.length - 1]) }}</p>
                        </div>
                        <div class="text-stone-700 dark:text-stone-100 flex flex-col items-center">
                            <p>Revenu mensuel</p>
                            <p class="font-semibold">{{ getCurrency(data.safeWithdrawal) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ContainerApp>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ContainerApp from '../../../../components/layout/container/ContainerApp.vue';
import DualSlider from '../../../../components/input/DualSlider.vue';
import FinancialIndependenceChart from '../../../../components/chart/simulator/FinancialIndependenceChart.vue';
import Loader from '../../../../components/loader/Loader.vue';
import Icon from '../../../../components/icon/Icon.vue';
import Input from '../../../../components/input/Input.vue';
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
    currentHeritage: 10000,
    initialAllocation: 50,
    monthInvestment: 500,
    investmentAllocation: 50,
    savingYears: 20,
    stockYield: 8,
    otherYield: 4,
    withdrawalRate: 4,
    inflationRate: 3,
    stockTax: 17.2,
    otherTax: 30,
    withTax: false
});

const data = ref({
    years: [],
    interests: [],
    deposits: [],
    initialHeritage: [],
    total: [],
    safeWithdrawal: null
});

const loading = ref(false);

const handleSubmit = async () => {
    try {
        loading.value = true;

        const response = await post(props.api.calculateFinancialIndependence, formData.value);

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