<template>
    <h2 v-if="label" class="mb-3 text-stone-900 dark:text-stone-50">{{ label }}</h2>
    
    <table
        v-if="rows.length > 0 || loading"
        class="w-full relative shadow-sm rounded-lg text-sm text-left rtl:text-right text-stone-500 dark:text-stone-400 bg-stone-200 dark:bg-stone-800"
    >
        <Loader :loading="loading" />
        <thead class="text-xs text-stone-700 uppercase dark:text-stone-400">
            <tr>
                <th 
                    v-for="(column, index) in columns"
                    :key="index"
                    scope="col"
                    :title="column.label"
                    class="p-2 truncate max-w-20"
                >
                    {{ column.label }}
                </th>
                <th scope="col" class="px-2 py-1">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody>
            <tr 
                v-for="(row, rowIndex) in rows"
                :key="rowIndex"
                class="bg-stone-50 dark:bg-stone-900"
            >
                <td
                    v-for="(column, colIndex) in columns"
                    :key="colIndex"
                    scope="row"
                    :title="row[column.key]"
                    :class="[
                        { 'rounded-bl-lg': colIndex === 0 && rowIndex === rows.length - 1 }, 
                        'px-2 py-1 font-medium text-stone-900 truncate max-w-20 dark:text-stone-50',
                    ]"
                >
                    {{ row[column.key] }}
                </td>
                <td 
                    scope="row"
                    :class="[
                        { 'rounded-br-lg': rowIndex === rows.length - 1 },
                        'px-2 py-1'
                    ]"
                >
                    <button
                        type="button"
                        class="rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-600 cursor-pointer"
                        :data-dropdown-toggle="`dropdown-${id}-${rowIndex}`"
                        data-dropdown-placement="bottom"
                        @click=""
                    >
                        <span class="sr-only">Actions</span>
                        <Icon class="size-6 text-stone-700 dark:text-stone-300" name="Ellipsis" />
                    </button>
                    <div
                        :id="`dropdown-${id}-${rowIndex}`"
                        class="absolute flex flex-col hidden z-10 w-max divide-y divide-stone-200 dark:divide-stone-700 bg-stone-100 border border-stone-200 rounded-lg shadow-md dark:border-stone-700 dark:bg-stone-800"
                    >
                        <div class="p-2 font-semibold text-center">
                            Actions
                        </div>
                        <div class="p-2">
                            <button 
                                v-if="actions.modify"
                                class="relative flex items-center w-full p-2 text-stone-700 rounded-lg dark:text-stone-400 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer"
                                :data-modal-target="`modal-modify-${id}-${rowIndex}`"
                                :data-modal-toggle="`modal-modify-${id}-${rowIndex}`"
                            >
                                <Icon class="size-5 me-2" name="Pen" />Modifier
                            </button>
                            <button
                                v-if="actions.remove"
                                class="relative flex items-center w-full p-2 text-stone-700 rounded-lg dark:text-stone-400 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer"
                                :data-modal-target="`modal-remove-${id}-${rowIndex}`"
                                :data-modal-toggle="`modal-remove-${id}-${rowIndex}`"
                            >
                                <Icon class="size-5 me-2" name="X" />Supprimer
                            </button>
                            <button v-if="actions.preleve" class="relative flex items-center w-full p-2 text-stone-700 rounded-lg dark:text-stone-400 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer">
                                <Icon class="size-5 me-2" name="TicketCheck" />Prélevé
                                <Badge>Soon</Badge>
                            </button>
                        </div>
                    </div>
                    <ModalUpdate
                        v-if="actions.modify"
                        :id="`modal-modify-${id}-${rowIndex}`"
                        title="Modifier"
                        :update="{
                            fields: update?.fields,
                            item: update.data?.find(item => item.id === row.id)
                        }"
                        @update="(newData) => emit('update', { id: row.id, data: newData })"
                    />
                    <ModalDelete
                        v-if="actions.remove"
                        :id="`modal-remove-${id}-${rowIndex}`"
                        title="Supprimer"
                        @delete="emit('delete', row.id)"
                    />
                </td>
            </tr>
        </tbody>
    </table>

    <div v-else class="w-full relative shadow-sm rounded-lg flex items-center justify-center py-5 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
        Aucune donnée disponible
    </div>
</template>

<script setup>
import { nextTick, watch } from 'vue';
import Badge from '../badge/Badge.vue';
import Icon from '../icon/Icon.vue';
import Loader from '../loader/Loader.vue';
import ModalDelete from '../modal/ModalDelete.vue';
import ModalUpdate from '../modal/ModalUpdate.vue';
import { initDropdowns, initModals } from 'flowbite';

const emit = defineEmits(['update', 'delete']);

const props = defineProps({
    id: { type: String, required: true },
    label: { type: String, required: false },
    columns: { type: Array, required: true },
    rows: { type: Array, required: true },
    actions: { type: Object, default: () => ({ }) },
    update: { type: Object, default: () => ({}) },
    loading: { type: Boolean, default: false }
});

watch(
    () => props.rows,
    async (newRows) => {
        if (newRows && newRows.length > 0) {
            await nextTick();
            initDropdowns();
            initModals();
        }
    },
    { immediate: false, deep: true }
);
</script>