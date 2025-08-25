<template>
    <Modal
        :id="id"
        :title="title"
    >
        <template #body>
            <form @submit.prevent>
                <div class="mb-3 grid grid-cols-2 gap-3">
                    <component
                        v-for="field in update.fields"
                        :is="field.input"
                        :key="field.key"
                        :id="`${id}-${field.key}`"
                        :label="field.label"
                        :name="`${id}-${field.key}`"
                        :disabled="field.disabled ?? false"
                        v-model="update.item[field.key]"
                        v-bind="getComponentProps(field)"
                    />
                </div>
            </form>
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
                class="ms-2 text-green-500 border border-green-500 dark:text-green-400 hover:bg-green-200/30 dark:border-green-400 dark:hover:bg-green-700/40 hover:text-green-900 dark:hover:text-green-300 rounded-lg p-2 cursor-pointer"
                :data-modal-hide="id"
                @click="$emit('update', update.item)"
            >
                Modifier
            </button>
        </template>
    </Modal>
</template>

<script setup>
import Modal from '../modal/Modal.vue';

const emit = defineEmits(['update']);

const props = defineProps({
    id: { type: String, required: true },
    title: { type: String, required: true },
    update: { type: Object, default: () => ([]) }
});

const getComponentProps = (field) => {
    const props = {};

    if (field.type) {
        props.type = field.type;
    }
    if (field.icon) {
        props.icon = field.icon;
    }
     if (field.items) {
        props.items = field.items;
    }

    return props;
};
</script>