<template>
    <div>
        <h2 class="mb-3 text-stone-900 dark:text-stone-50">Restant du mois précédent</h2>

        <div v-if="data.length > 0" class="grid gap-3">
            <MovementItemCard
                v-for="(item, index) in data"
                :key="index"
                id="remaining-previous"
                :data="item"
                :actions="{
                    charge: false,
                    move: false,
                    name: false,
                    remove: false,
                    removeConfirm: true
                }"
                @update="handleUpdate"
            />
        </div>

        <div v-else class="w-full relative shadow-sm rounded-lg flex items-center justify-center py-5 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
            <Loader :loading="loading" class="rounded-lg"/>
            Aucune donnée disponible
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, inject } from 'vue';
import Loader from '../../../../../loader/Loader.vue';
import MovementItemCard from '../../../../../card/MovementItemCard.vue';

import useApi  from '../../../../../../composables/useApi';
import { useToast } from '../../../../../../plugins/useToast';

const { get, put } = useApi();
const toast = useToast();

const emit = defineEmits(['update']);

const props = defineProps({
    api: { type: Object, required: true }
});

const formDataDate = inject('formDataDate');

const loading = ref(false);
const data = ref([]);

const getRemainingPrevious = async () => {
    try {
        loading.value = true;
        const response = await get(props.api.getRemainingPrevious.replace('month', formDataDate.value.month).replace('year', formDataDate.value.year));

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

const handleUpdate = async (data) => {
    try {
        const response = await put(props.api.update.replace('id', data.id), data);

        if (response.success) {
            await getRemainingPrevious();
            toast.default('🎉 Modifié avec succès !');
            emit('update');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

watch(() => formDataDate.value, async () => {
    await getRemainingPrevious();
}, { deep: true });

onMounted(async () => {
    await getRemainingPrevious();
});
</script>