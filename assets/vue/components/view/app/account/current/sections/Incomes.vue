<template>
    <div>
        <div class="flex justify-between mb-3">
            <h2 class="text-stone-900 dark:text-stone-50">Salaire et autres revenus</h2>
            <button
                type="button"
                class="flex flex-row items-center text-sm text-stone-400 hover:text-stone-800 dark:hover:text-stone-100 hover:underline transition cursor-pointer"
                data-modal-target="modal-add-incomes"
                data-modal-toggle="modal-add-incomes"
            >
                <Icon class="size-4 me-1" name="Plus" />
                Ajouter
            </button>
        </div>

        <VueDraggable
            v-if="data.length > 0"
            class="grid gap-3"
            ghost-class="opacity-25"
            handle=".handle"
            :disabled="data.length === 1"
            :animation="200"
            v-model="data"
            @start=""
            @update=""
            @end=""
        >
            <MovementItemCard
                v-for="(item, index) in data"
                :key="index"
                id="incomes"
                :data="item"
                :actions="{
                    charge: false,
                    move: data.length > 1,
                    name: true,
                    remove: data.length > 1,
                    removeConfirm: true
                }"
                @update="handleUpdate"
                @delete="handleDelete"
            />
        </VueDraggable>

        <div v-else class="w-full relative shadow-sm rounded-lg flex items-center justify-center py-5 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
            <Loader :loading="loading" class="rounded-lg"/>
            Aucune donnée disponible
        </div>

        <ModalAdd
            ref="modal"
            id="modal-add-incomes"
            title="Ajouter un revenu"
            :charge="false"
            @add="handleSubmit"
        />
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch, provide, inject } from 'vue';
import Icon from '../../../../../icon/Icon.vue';
import Loader from '../../../../../loader/Loader.vue';
import ModalAdd from '../../../../../modal/ModalAdd.vue';
import MovementItemCard from '../../../../../card/MovementItemCard.vue';

import useApi  from '../../../../../../composables/useApi';
import useGenerateId from '../../../../../../composables/useGenerateId';
import { useToast } from '../../../../../../plugins/useToast';
import { VueDraggable } from 'vue-draggable-plus';

const { get, post, put, del } = useApi();
const { uniqueId } = useGenerateId();
const toast = useToast();

const emit = defineEmits(['update']);

const props = defineProps({
    api: { type: Object, required: true }
});

const formDataDate = inject('formDataDate');

const modal = ref(null);
const loading = ref(false);
const data = ref([]);

const createDefaultData = () => ({
    id: uniqueId(),
    name: '',
    amount: 0,
    transactionDirection: 'credit',
});

const formData = reactive({
    collection: [createDefaultData()]
});

provide('createDefaultData', createDefaultData);
provide('formData', formData);

const getIncomes = async () => {
    try {
        loading.value = true;
        const response = await get(props.api.getIncomes.replace('month', formDataDate.value.month).replace('year', formDataDate.value.year));

        if (response.success) {
            data.value = response.data.map(item => {
                return {
                    id: item.id,
                    name: item.name,
                    amount: item.amount,
                    transactionDirection: item.transactionDirection,
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
        const payload = {
            collection: formData.collection.map(({id, ...item}) => ({
                ...item,
            }))
        };
        const response = await post(props.api.getIncomes.replace('month', formDataDate.value.month).replace('year', formDataDate.value.year), payload);

        if (response.success) {
            modal.value.close();
            loading.value = false;
            toast.default('🎉 Ajouté avec succès !');
            await getIncomes();
            emit('update');
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
        const response = await put(props.api.update.replace('id', data.id), data);

        if (response.success) {
            await getIncomes();
            toast.default('🎉 Modifié avec succès !');
            emit('update');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleDelete = async (id) => {
    try {
        const response = await del(props.api.delete.replace('id', id));

        if (response.success) {
            await getIncomes();
            toast.default('🎉 Supprimé avec succès !');
            emit('update');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

watch(() => formDataDate.value, async () => {
    await getIncomes();
}, { deep: true });

onMounted(async () => {
    await getIncomes();
});
</script>