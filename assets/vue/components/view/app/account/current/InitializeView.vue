<template>
    <div class="rounded-lg shadow-sm bg-stone-50 dark:bg-stone-900 p-4">
        <form @submit.prevent="handleSubmit">
            <h3 class="text-lg text-stone-900 dark:text-stone-50 mb-2">Solde de départ</h3>
            <Input
                id="input_starting_balance"
                class="mb-3"
                name="startingBalance"
                type="number"
                v-model="formData.startingBalance"
                :icon="{
                    direction: 'right',
                    name: 'Euro'
                }"
                :is-error="errors.startingBalance.isError"
                :error-message="errors.startingBalance.message"
            />

            <h3 class="text-lg text-stone-900 dark:text-stone-50 mb-2">Salaire et autres revenus</h3>
            <InputRepeater
                id="incomes"
                class="mb-3"
                :fields="[
                    { key: 'name', label: 'Nom', input: Input, type: 'text' },
                    { key: 'amount', label: 'Montant', input: Input, type: 'number', icon: { direction: 'right', name: 'Euro' } }
                ]"
                v-model:items="formData.incomes"
            />

            <h3 class="text-lg text-stone-900 dark:text-stone-50 mb-2">Dépenses fixes</h3>
            <InputRepeater
                id="fixed-expenses"
                class="mb-5"
                :fields="[
                    { key: 'name', label: 'Nom', input: Input, type: 'text' },
                    { key: 'amount', label: 'Montant', input: Input, type: 'number', icon: { direction: 'right', name: 'Euro' } },
                    { key: 'day', label: 'Prélevé le', input: DayPicker }
                ]"
                v-model:items="formData.fixedExpenses"
            />

            <Button 
                class="w-full"
                color="gradient"
                type="submit"
                :loading="loading"
                :disabled="loading"
            >
                Initialiser le compte courant
            </Button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Button from '../../../../button/Button.vue';
import DayPicker from '../../../../input/DayPicker.vue';
import InputRepeater from '../../../../input/InputRepeater.vue';
import Input from '../../../../input/Input.vue';

import useApi from '../../../../../composables/useApi';

const { post } = useApi();

const props = defineProps({
    url: { type: Object, required: true },
    api: { type: Object, required: true },
    date: { type: Object, required: true },
});

const loading = ref(false);

const formData = ref({
    startingBalance: 0,
    incomes: [
        {
            name: 'Salaire',
            amount: 0
        }
    ],
    fixedExpenses: [
        {
            name: 'Loyer',
            amount: 0,
            day: 1
        }
    ],
    month: props.date.month,
    year: props.date.year
});

const errors = ref({
    startingBalance: {
        isError: false,
        message: ''
    },
});

const handleSubmit = async () => {
    try {
        loading.value = true;
        const response = await post(props.api.initializeAccount, formData.value);

        if (response.success) {
            loading.value = false;
            window.location.href = props.url.current;
        } else {
            loading.value = false;
            for (const [key, messages] of Object.entries(response.errors)) {
                if (errors.value[key]) {
                    errors.value[key].isError = true;
                    errors.value[key].message = messages[0];
                }
            }
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};
</script>