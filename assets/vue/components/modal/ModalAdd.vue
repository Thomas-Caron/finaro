<template>
    <Modal
        ref="modal"
        :id="id"
        :title="title"
        @close="close"
    >
        <template #body>
            <div class="grid gap-3">
                <MovementItemCard
                    v-for="(item, index) in formData.collection"
                    :key="index"
                    id="expenses"
                    :data="item"
                    :labels="labels"
                    :actions="{
                        charge: charge,
                        move: false,
                        name: true,
                        remove: formData.collection.length > 1,
                        removeConfirm: false
                    }"
                    @delete="remove(index)"
                />
                <div class="flex justify-end">
                    <button
                        type="button"
                        class="flex flex-row items-center text-sm text-stone-400 hover:text-stone-800 dark:hover:text-stone-100 hover:underline transition cursor-pointer"
                        @click.stop="add"
                    >
                        <Icon class="size-4 me-1" name="Plus" />
                        Ajouter
                    </button>
                </div>
            </div>
        </template>
        <template #footer>
            <Button
                class="w-full"
                color="gradient"
                type="button"
                :loading="loading"
                :disabled="loading"
                @click.stop="submit"
            >
                Ajouter
            </Button>
        </template>
    </Modal>
</template>

<script setup>
import { ref, inject } from 'vue';
import Button from '../button/Button.vue';
import Modal from '../modal/Modal.vue';
import MovementItemCard from '../card/MovementItemCard.vue';
import Icon from '../icon/Icon.vue';

import { useToast } from '../../plugins/useToast';

const toast = useToast();

const emit = defineEmits(['add']);

const modal = ref(null);
const loading = ref(false);
const createDefaultData = inject('createDefaultData');
const formData = inject('formData');
const labels = inject('labels');

const props = defineProps({
    id: { type: String, required: true },
    title: { type: String, required: true },
    charge: { type: Boolean, default: true }
});

const submit = () => {
    const hasEmptyName = formData.collection.some(item => !item.name?.trim());

    if (hasEmptyName) {
        toast.error('❌ Le nom ne peut pas être vide');
        return;
    }

    loading.value = true;
    emit('add');
};

const add = async () => {
    formData.collection.push(createDefaultData());
};

const remove = (index) => {
    formData.collection.splice(index, 1);
};

const close = () => {
    modal.value.close();
    formData.collection = [createDefaultData()];
    loading.value = false;
};

defineExpose({ close });
</script>