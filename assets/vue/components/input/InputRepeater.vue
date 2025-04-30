<template>
    <div class="rounded-lg shadow-sm bg-stone-100 dark:bg-stone-800/40 p-4">
        <div
            v-if="fields.length"
            class="flex items-end mb-2 gap-3 text-sm text-stone-900 dark:text-stone-50"
        >
            <p
                v-for="field in fields"
                :key="field.key"
                class="flex-1"
            >
                {{ field.label }}
            </p>
            <div v-if="items.length > 1" class="size-6"></div>
        </div>

        <div
            v-for="(item, index) in localItems"
            :key="index"
            class="mb-3 flex items-end gap-3"
        >
            <component
                v-for="field in fields"
                :is="field.input"
                :key="field.key"
                :id="`${id}-${field.key}-${index}`"
                class="flex-1"
                :name="`${id}-${field.key}-${index}`"
                v-model="item[field.key]"
                v-bind="getComponentProps(field)"
            />

            <button
                v-if="items.length > 1"
                type="button"
                @click="remove(index)"
                class="self-center rounded-full p-1 border border-stone-400 text-stone-400 hover:bg-stone-300/30 dark:hover:bg-stone-700/30 hover:text-stone-800 dark:hover:text-stone-100 hover:border-stone-800 dark:hover:border-stone-100 transition cursor-pointer"
            >
                <Icon class="size-6" name="X" />
            </button>
        </div>

        <div class="flex justify-end">
            <button
                type="button"
                class="flex flex-row items-center text-sm text-stone-400 hover:text-stone-800 dark:hover:text-stone-100 hover:underline transition cursor-pointer"
                @click="add"
            >
                <Icon class="size-4 me-1" name="Plus" />
                Ajouter
            </button>
        </div>
    </div>
</template>

<script setup>
import { reactive, watch, nextTick } from 'vue';
import Icon from '../icon/Icon.vue';
import { initDropdowns } from 'flowbite';

const props = defineProps({
    id: { type: String, required: true },
    items: { type: Array, default: () => ([]) },
    fields: { type: Object, default: () => ([]) }
});

const emit = defineEmits(['update:items']);

const localItems = reactive([...props.items]);

watch(
    () => props.items,
    (newItems) => {
        localItems.splice(0, localItems.length, ...newItems);
    },
    { deep: true }
);

watch(
    () => localItems,
    (newVal) => emit('update:items', newVal),
    { deep: true }
);

const add = async () => {
    localItems.push({ ...localItems[0] });
    await nextTick();
    initDropdowns();
};

const remove= (index) => {
    if (localItems.length > 1) {
        localItems.splice(index, 1);
    }
};

const getComponentProps = (field) => {
    const props = {};

    if (field.type) {
        props.type = field.type;
    }
    if (field.icon) {
        props.icon = field.icon;
    }

    return props;
};
</script>