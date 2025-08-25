<template>
    <div class="rounded-lg shadow-sm bg-stone-50 dark:bg-stone-900 p-4">
        <h3 class="text-lg text-stone-900 dark:text-stone-50 mb-2">Solde de départ</h3>
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

        <Button 
            class="w-full mt-3"
            color="gradient"
            type="button"
            :loading="loading"
            :disabled="loading"
            @click.stop="handleSubmit"
        >
            Initialiser le compte courant
        </Button>
    </div>
</template>

<script setup>
import { ref, reactive, inject } from 'vue';
import Button from '../../../../button/Button.vue';
import Icon from '../../../../icon/Icon.vue';
import MovementItemCard from '../../../../card/MovementItemCard.vue';

import useApi from '../../../../../composables/useApi';
import useGenerateId from '../../../../../composables/useGenerateId';
import useDateFormat from '../../../../../composables/useDateFormat';
import { useToast } from '../../../../../plugins/useToast';

const { post } = useApi();
const { uniqueId } = useGenerateId();
const { getIndexMonth } = useDateFormat();
const toast = useToast();

const props = defineProps({
    url: { type: Object, required: true },
    api: { type: Object, required: true },
});

const loading = ref(false);
const formDataDate = inject('formDataDate');
const labels = inject('labels');

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
        const response = await post(props.api.initializeAccount, payload);

        if (response.success) {
            loading.value = false;
            toast.default('🎉 Compte courant initialisé avec succès !');
            window.location.href = props.url.current;
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
</script>