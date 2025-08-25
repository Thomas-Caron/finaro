<template>
    <Modal
        ref="modal"
        :id="props.id"
        :title="props.title"
        class="max-w-[730px]"
    >
        <template #body>
            <div class="p-4 rounded-lg bg-white">
                <div role="status" class="animate-pulse mb-5">
                    <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-[450px] mb-2.5"></div>
                    <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-2.5" v-for="i in 4" :key="i"></div>
                </div>

                <div id="signature-preview">
                    <table cellpadding="0" cellspacing="0" border="0" :style="styleToString(form.styles.container)">
                        <tbody>
                            <tr>
                                <td style="text-align: left; white-space: nowrap;">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                        <tbody>
                                            <tr>
                                                <template v-for="column in orderedColumns" :key="column.id">
                                                    <td :style="styleToString(form.styles[column.id])">
                                                        <table cellpadding="0" cellspacing="0" border="0" :width="form.styles[column.id].width">
                                                            <tbody>
                                                                <tr
                                                                    v-for="block in getChildrenOfColumn(column.id)"
                                                                    :key="block.id"
                                                                >
                                                                    <td :style="styleToString(form.styles[block.id])">
                                                                        <p style="margin: .1px;">
                                                                            <span v-if="block.type === 'url'">
                                                                                <a :href="block.value" :style="styleToString(form.styles[block.id])">
                                                                                    {{ block.label }}
                                                                                </a>
                                                                            </span>
                                                                            <span v-else-if="block.type === 'image'">
                                                                                <img :src="block.value" :style="form.styles[block.id]" />
                                                                            </span>
                                                                            <span v-else>{{ block.value }}</span>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </template>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>

        <template #footer>
            <button
                type="button"
                class="w-full p-4 flex flex-row justify-center items-center rounded-lg text-stone-200 dark:text-stone-600 bg-stone-800 dark:bg-stone-200 hover:bg-stone-600 dark:hover:bg-stone-400 border-t border-stone-200 dark:border-stone-700 transition duration-300 cursor-pointer"
                @click="copyHtml"
            >
                Copier le HTML
            </button>
        </template>
    </Modal>
</template>

<script setup>
import { inject, computed } from 'vue';
import Modal from '../../modal/Modal.vue';

import { useToast } from '../../../plugins/useToast';

const toast = useToast();

const props = defineProps({
    id: { type: String, required: true },
    title: { type: String, required: true }
});

const form = inject('form');

const columnBlocks = computed(() => {
    return form.blocks.filter(b => b.type === 'column');
});

const orderedColumns = computed(() => {
    return [...columnBlocks.value].sort((a, b) => {
        if (a.position === 'left') return -1;
        if (b.position === 'left') return 1;
        if (a.position === 'right') return 1;
        if (b.position === 'right') return -1;
        return 0;
    });
});

const getChildrenOfColumn = (columnId) => {
    return form.blocks.filter(b => b.parent === columnId);
};

const styleToString = (styleObj) => {
    if (!styleObj) return '';
    return Object.entries(styleObj)
        .map(([k, v]) => `${k.replace(/[A-Z]/g, m => '-' + m.toLowerCase())}:${v}`)
        .join(';');
};

const copyHtml = async () => {
    const preview = document.querySelector('#signature-preview');
    if (!preview) {
        toast.error("Signature introuvable");
        return;
    }
    const html = preview.innerHTML;
    try {
        await navigator.clipboard.write([
            new ClipboardItem({
                'text/html': new Blob([html], { type: 'text/html' })
            })
        ]);
        toast.success("Signature copiée !");
    } catch (error) {
        console.log(error);
        toast.error("Impossible de copier le HTML.");
    }
};
</script>