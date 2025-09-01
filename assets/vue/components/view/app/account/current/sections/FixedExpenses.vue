<template>
    <div>
        <div class="flex justify-between mb-3">
            <h2 class="text-stone-900 dark:text-stone-50">Dépenses fixes</h2>
            <button
                type="button"
                class="flex flex-row items-center text-sm text-stone-400 hover:text-stone-800 dark:hover:text-stone-100 hover:underline transition cursor-pointer"
                data-modal-target="modal-add-fixed-expenses"
                data-modal-toggle="modal-add-fixed-expenses"
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
            @start="console.log('drag start', data)"
            @update=""
            @end="console.log('drag end', data)"
        >
            <MovementItemCard
                v-for="(item, index) in data"
                :key="index"
                id="fixed-expenses"
                :data="item"
                :labels="labels"
                :actions="{
                    charge: true,
                    move: data.length > 1,
                    name: true,
                    remove: true,
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
            id="modal-add-fixed-expenses"
            title="Ajouter une dépense fixe"
            :charge="true"
            @add="handleSubmit"
        />
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch, inject, provide, watchEffect } from 'vue';
import Icon from '../../../../../icon/Icon.vue';
import Loader from '../../../../../loader/Loader.vue';
import ModalAdd from '../../../../../modal/ModalAdd.vue';
import MovementItemCard from '../../../../../card/MovementItemCard.vue';

import useApi  from '../../../../../../composables/useApi.js';
import useDateFormat from '../../../../../../composables/useDateFormat.js';
import useGenerateId from '../../../../../../composables/useGenerateId';
import { useToast } from '../../../../../../plugins/useToast.js';
import { VueDraggable } from 'vue-draggable-plus';

const { get, post, put, del } = useApi();
const { toDay, getIndexMonth } = useDateFormat();
const { uniqueId } = useGenerateId();
const toast = useToast();

const emit = defineEmits(['update']);

const props = defineProps({
    api: { type: Object, required: true }
});

const modal = ref(null);
const loading = ref(false);
const data = ref([]);
const labels = inject('labels');
const formDataDate = inject('formDataDate');

const createDefaultData = () => ({
    id: uniqueId(),
    name: '',
    label: labels.value[0],
    amount: 0,
    formatDay: new Date(formDataDate.value.year, getIndexMonth(formDataDate.value.month) - 1, 1),
    day: 1,
    transactionDirection: 'debit',
    isCharged: false
});

const formData = reactive({
    collection: [createDefaultData()]
});

provide('createDefaultData', createDefaultData);
provide('formData', formData);

const getFixedExpenses = async () => {
    try {
        loading.value = true;
        const response = await get(props.api.getFixedExpenses.replace('month', formDataDate.value.month).replace('year', formDataDate.value.year));

        if (response.success) {
            data.value = response.data.map(item => {
                return {
                    id: item.id,
                    label: {
                        id: item.label.id,
                        name: item.label.name,
                        color: item.label.color,
                        icon: item.label.icon
                    },
                    name: item.name,
                    amount: item.amount,
                    transactionDirection: item.transactionDirection,
                    formatDay: item.chargedAt,
                    day: toDay(item.chargedAt),
                    isCharged: item.isCharged
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
                label: item.label?.id ?? null,
            }))
        };

        const response = await post(props.api.getFixedExpenses.replace('month', formDataDate.value.month).replace('year', formDataDate.value.year), payload);

        if (response.success) {
            modal.value.close();
            loading.value = false;
            toast.default('🎉 Ajouté avec succès !');
            await getFixedExpenses();
            emit('update');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleUpdate = async (val) => {
    let date = new Date(data.value.find((item) => item.id === val.id).formatDay);
    date.setDate(val.day);
    data.value.find((item) => item.id === val.id).formatDay = date.toISOString();

    try {
        const response = await put(props.api.update.replace('id', val.id), val);

        if (response.success) {
            toast.default('🎉 Modifié avec succès !');
            emit('update');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleDelete = async (id) => {
    data.value = data.value.filter((item) => item.id !== id);
    try {
        const response = await del(props.api.delete.replace('id', id));

        if (response.success) {
            toast.default('🎉 Supprimé avec succès !');
            emit('update');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

watch(() => formDataDate.value, async () => {
    await getFixedExpenses();
}, { deep: true });

onMounted(async () => {
    await getFixedExpenses();
});
</script>