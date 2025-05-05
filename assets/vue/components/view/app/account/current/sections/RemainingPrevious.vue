<template>
    <div>
        <h2 class="mb-3 text-stone-900 dark:text-stone-50">Restant du mois précédent</h2>
        <Table
            id="remaining-previous"
            :columns="[
                { key: 'name', label: 'Nom' },
                { key: 'amount', label: 'Montant' },
            ]"
            :rows="dataTable"
            :actions="{
                modify: true,
                remove: false,
                preleve: false
            }"
            :loading="loading"
            :update="{
                fields: [
                    { key: 'amount', label: 'Montant', input: Input, type: 'number', icon: { direction: 'right', name: 'Euro' } }
                ],
                data: data
            }"
            @update="handleUpdate"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Input from '../../../../../input/Input.vue';
import Table from '../../../../../table/Table.vue';
import useApi  from '../../../../../../composables/useApi.js';
import useConvertFilter from '../../../../../../composables/useConvertFilter.js';

const { get, put } = useApi();
const { getCurrency } = useConvertFilter();

const props = defineProps({
    api: { type: Object, required: true },
    date: { type: Object, required: true }
});

const loading = ref(false);
const data = ref([]);
const dataTable = ref([]);

const getRemainingPrevious = async () => {
    try {
        loading.value = true;
        const response = await get(props.api.getRemainingPrevious.replace('month', props.date.month).replace('year', props.date.year));

        if (response.success) {
            data.value = response.data.map(item => {
                return {
                    id: item.id,
                    name: item.name,
                    amount: item.amount,
                };
            });
            dataTable.value = response.data.map(item => {
                return {
                    id: item.id,
                    name: item.name,
                    amount: getCurrency(item.amount),
                };
            });
            loading.value = false;
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleUpdate = async (data) => {
    try {
        const response = await put(props.api.update.replace('id', data.id), data.data);

        if (response.success) {
            await getRemainingPrevious();
            toast.default('🎉 Modifié avec succès !');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

onMounted(async () => {
    await getRemainingPrevious();
});
</script>