<template>
    <div>
        <div class="flex justify-between mb-3">
            <h2 class="text-stone-900 dark:text-stone-50">Dépenses</h2>
            <button
                type="button"
                class="flex flex-row items-center text-sm text-stone-400 hover:text-stone-800 dark:hover:text-stone-100 hover:underline transition cursor-pointer"
                data-modal-target="modal-add-expenses"
                data-modal-toggle="modal-add-expenses"
            >
                <Icon class="size-4 me-1" name="Plus" />
                Ajouter
            </button>
        </div>
        
        <Table
            id="expenses"
            :columns="[
                { key: 'label', label: 'Catégorie' },
                { key: 'name', label: 'Nom' },
                { key: 'amount', label: 'Montant' },
            ]"
            :rows="dataTable"
            :actions="{
                modify: true,
                remove: true,
                preleve: true
            }"
            :loading="loading"
            :update="{
                fields: [
                    { key: 'name', label: 'Nom', input: Input, type: 'text' },
                    { key: 'amount', label: 'Montant', input: Input, type: 'number', icon: { direction: 'right', name: 'Euro' } }
                ],
                data: data
            }"
            @update="handleUpdate"
            @delete="handleDelete"
        />

        <Modal
            ref="modal"
            id="modal-add-expenses"
            title="Ajouter une dépense"
            @close="formData.collection = [{ name: '', amount: 0 }]"
        >
            <template #body>
                <form @submit.prevent="handleSubmit">
                    <InputRepeater
                        id="modal-form-expenses"
                        class="mb-5"
                        :fields="[
                            { key: 'name', label: 'Nom', input: Input, type: 'text' },
                            { key: 'amount', label: 'Montant', input: Input, type: 'number', icon: { direction: 'right', name: 'Euro' } }
                        ]"
                        v-model:items="formData.collection"
                    />

                    <Button 
                        class="w-full"
                        color="gradient"
                        type="submit"
                        :loading="loading"
                        :disabled="loading"
                    >
                        Ajouter
                    </Button>
                </form>
            </template>
        </Modal>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Button from '../../../../../button/Button.vue';
import Icon from '../../../../../icon/Icon.vue';
import Input from '../../../../../input/Input.vue';
import InputRepeater from '../../../../../input/InputRepeater.vue';
import Modal from '../../../../../modal/Modal.vue';
import Table from '../../../../../table/Table.vue';
import useApi  from '../../../../../../composables/useApi.js';
import useConvertFilter from '../../../../../../composables/useConvertFilter.js';
import { useToast } from '../../../../../../plugins/useToast.js';

const { get, post, put, del } = useApi();
const { getCurrency } = useConvertFilter();
const toast = useToast();

const props = defineProps({
    api: { type: Object, required: true },
    date: { type: Object, required: true }
});

const modal = ref(null);
const loading = ref(false);
const data = ref([]);
const dataTable = ref([]);

const formData = ref({
    collection: [
        {
            name: '',
            amount: 0
        }
    ]
});

const getExpenses = async () => {
    try {
        loading.value = true;
        const response = await get(props.api.getExpenses.replace('month', props.date.month).replace('year', props.date.year));

        if (response.success) {
            data.value = response.data;
            dataTable.value = response.data.map(item => {
                return {
                    id: item.id,
                    label: 'À venir',
                    name: item.name,
                    amount: item.amount,
                };
            });
            dataTable.value = response.data.map(item => {
                return {
                    id: item.id,
                    label: 'À venir',
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

const handleSubmit = async () => {
    try {
        loading.value = true;
        const response = await post(props.api.getExpenses.replace('month', props.date.month).replace('year', props.date.year), formData.value);

        if (response.success) {
            loading.value = false;
            modal.value.close();
            await getExpenses();
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

const handleUpdate = async (data) => {
    try {
        const response = await put(props.api.update.replace('id', data.id), data.data);

        if (response.success) {
            await getExpenses();
            toast.default('🎉 Modifié avec succès !');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleDelete = async (id) => {
    try {
        const response = await del(props.api.delete.replace('id', id));

        if (response.success) {
            await getExpenses();
            toast.default('🎉 Supprimé avec succès !');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

onMounted(async () => {
    await getExpenses();
});
</script>