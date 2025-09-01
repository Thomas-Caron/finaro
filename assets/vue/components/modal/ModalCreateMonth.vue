<template>
    <Modal
        ref="modal"
        :id="id"
        :title="title"
    >
        <template #body>
            <form @submit.prevent>
                <Select
                    v-if="months.length > 0"
                    id="select_month"
                    class="mb-3"
                    name="month"
                    placeholder="Sélectionnez un mois"
                    :items="months"
                    v-model="formData.month"
                />

                <h3 class="text-lg text-stone-900 dark:text-stone-50 my-2">Restant du mois précédent</h3>
                <div class="grid gap-3">
                    <MovementItemCard
                        v-for="(item, index) in formData.remainingPrevious"
                        :key="index"
                        id="remaining-previous"
                        :data="item"
                        :labels="labels"
                        :actions="{
                            charge: false,
                            move: false,
                            name: false,
                            remove: false,
                            removeConfirm: false
                        }"
                    />
                </div>

                <h3 class="text-lg text-stone-900 dark:text-stone-50 my-2">Salaire et autres revenus</h3>
                <div class="grid gap-3">
                    <MovementItemCard
                        v-for="(item, index) in formData.incomes"
                        :key="index"
                        id="incomes"
                        :data="item"
                        :labels="labels"
                        :actions="{
                            charge: false,
                            move: false,
                            name: true,
                            remove: formData.incomes.length > 1,
                            removeConfirm: false
                        }"
                        @delete="handleRemove(index, 'incomes')"
                    />
                    <div class="flex justify-end">
                        <button
                            type="button"
                            class="flex flex-row items-center text-sm text-stone-400 hover:text-stone-800 dark:hover:text-stone-100 hover:underline transition cursor-pointer"
                            @click.stop="handleAdd('incomes')"
                        >
                            <Icon class="size-4 me-1" name="Plus" />
                            Ajouter
                        </button>
                    </div>
                </div>

                <h3 class="text-lg text-stone-900 dark:text-stone-50 my-2">Dépenses fixes</h3>
                <div class="grid gap-3">
                    <MovementItemCard
                        v-for="(item, index) in formData.fixedExpenses"
                        :key="index"
                        id="fixed-expenses"
                        :data="item"
                        :labels="labels"
                        :actions="{
                            charge: true,
                            move: false,
                            name: true,
                            remove: formData.fixedExpenses.length > 1,
                            removeConfirm: false
                        }"
                        @delete="handleRemove(index, 'fixedExpenses')"
                    />
                    <div class="flex justify-end">
                        <button
                            type="button"
                            class="flex flex-row items-center text-sm text-stone-400 hover:text-stone-800 dark:hover:text-stone-100 hover:underline transition cursor-pointer"
                            @click.stop="handleAdd('fixedExpenses')"
                        >
                            <Icon class="size-4 me-1" name="Plus" />
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </template>

        <template #footer>
            <Button
                class="w-full"
                color="gradient"
                type="button"
                :loading="loading"
                :disabled="loading"
                @click="handleSubmit"
            >
                Créer
            </Button>
        </template>
    </Modal>
</template>

<script setup>
import { ref, reactive, onMounted, inject, watch } from 'vue';
import Button from '../button/Button.vue';
import Icon from '../icon/Icon.vue';
import Modal from '../modal/Modal.vue';
import MovementItemCard from '../card/MovementItemCard.vue';
import Select from '../input/Select.vue';

import useApi  from '../../composables/useApi';
import useDateFormat from '../../composables/useDateFormat';
import useGenerateId from '../../composables/useGenerateId';
import { useToast } from '../../plugins/useToast';

const { get, post } = useApi();
const { toDay, getIndexMonth } = useDateFormat();
const { uniqueId } = useGenerateId();
const toast = useToast();

const props = defineProps({
    id: { type: String, required: true },
    title: { type: String, required: true },
    api: { type: Object, required: true },
    months: { type: Object, default: () => ({}) },
});

const modal = ref(null);
const loading = ref(false);
const labels = inject('labels');
const formDataDate = inject('formDataDate');

const createDefaultData = () => ({
    remainingPrevious: {
        name: 'Restant',
        amount: 0,
        transactionDirection: 'credit'
    },
    incomes: {
        id: uniqueId(),
        name: '',
        amount: 0,
        transactionDirection: 'credit',
    },
    fixedExpenses: {
        id: uniqueId(),
        name: '',
        label: labels.value[0],
        amount: 0,
        formatDay: new Date(formDataDate.value.year, getIndexMonth(formDataDate.value.month) - 1, 1),
        day: 1,
        transactionDirection: 'debit',
        isCharged: false
    }
});

const formData = reactive({
    ...formDataDate.value,
    remainingPrevious: [createDefaultData().remainingPrevious],
    incomes: [createDefaultData().incomes],
    fixedExpenses: [createDefaultData().fixedExpenses]
});

const getLastIncomes = async () => {
    try {
        const response = await get(props.api.getLastIncomes);

        if (response.success) {
            formData.incomes = response.data.map(item => {
                return {
                    name: item.name,
                    amount: item.amount,
                    transactionDirection: item.transactionDirection,
                };
            });
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const getLastFixedExpenses = async () => {
    try {
        const response = await get(props.api.getLastFixedExpenses);

        if (response.success) {
            formData.fixedExpenses = response.data.map(item => {
                return {
                    id: uniqueId(),
                    label: {
                        id: item.label.id,
                        name: item.label.name,
                        color: item.label.color,
                        icon: item.label.icon
                    },
                    name: item.name,
                    amount: item.amount,
                    transactionDirection: item.transactionDirection,
                    formatDay: new Date(formData.year, getIndexMonth(formData.month) - 1, toDay(item.chargedAt)),
                    day: toDay(item.chargedAt),
                    isCharged: item.isCharged
                };
            });
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleSubmit = async () => {
    try {
        loading.value = true;
        const payload = {
            ...formData,
            incomes: formData.incomes.map(({id, ...item}) => ({
                ...item
            })),
            fixedExpenses: formData.fixedExpenses.map(({id, ...item}) => ({
                ...item,
                label: item.label?.id ?? null
            }))
        };
        const response = await post(props.api.createMonth, payload);

        if (response.success) {
            loading.value = false;
            modal.value.close();
            toast.default('🎉 Mois créé avec succès !');
            window.location.reload();
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleAdd = async (type) => {
    formData[type].push(createDefaultData()[type]);
};

const handleRemove = (index, type) => {
    formData[type].splice(index, 1);
};

watch(
    () => formData.month, async () => {
        await getLastFixedExpenses();
    }, { immediate: true }
);

onMounted(async () => {
    await getLastIncomes();
    await getLastFixedExpenses();
});
</script>