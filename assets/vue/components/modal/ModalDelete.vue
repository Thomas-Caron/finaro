<template>
    <Modal
        :id="id"
        :title="title"
    >
        <template #body>
            <p class="text-stone-700 dark:text-stone-200">
                Êtes-vous sûr de vouloir supprimer cet élément ?
            </p>
            <p class="font-semi-bold text-stone-700 dark:text-stone-200">
                Cette action est irréversible.
            </p>
        </template>
        <template #footer>
            <button
                type="button"
                class="me-2 text-stone-500 border border-stone-500 dark:text-stone-400 hover:bg-stone-200/30 dark:border-stone-400 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 rounded-lg p-2 cursor-pointer"
                :data-modal-hide="id"
            >
                Annuler
            </button>
            <button
                type="button"
                class="ms-2 text-red-500 border border-red-500 dark:text-red-400 hover:bg-red-200/30 dark:border-red-400 dark:hover:bg-red-700/40 hover:text-red-900 dark:hover:text-red-300 rounded-lg p-2 cursor-pointer"
                :data-modal-hide="id"
                @click="$emit('delete')"
            >
                Supprimer
            </button>
        </template>
    </Modal>
</template>

<script setup>
import Modal from '../modal/Modal.vue';

const emit = defineEmits(['delete']);

const props = defineProps({
    id: { type: String, required: true },
    title: { type: String, required: true }
});
</script>