<template>
    <div class="p-4 rounded-lg bg-white w-[632px]">
        <div role="status" class="animate-pulse mb-5">
            <div class="h-2.5 bg-gray-200 rounded-full dark:bg-gray-700 w-48 mb-4"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-[450px] mb-2.5"></div>
            <div class="h-2 bg-gray-200 rounded-full dark:bg-gray-700 w-full mb-2.5" v-for="i in 4" :key="i"></div>
        </div>

        <div id="editable-signature" class="signature editable-signature">
            <div
                :class="[
                    {
                        'ring ring-blue-500 ring-offset-2': selectedBlockId === 'container'
                    },
                    'editable-signature-inside current relative hover:ring hover:ring-stone-400 hover:ring-offset-2 group'
                ]"
                :style="form.styles.container"
                @click.stop="select('container')"
            >
                <div class="flex items-stretch">
                    <!-- Colonne gauche -->
                    <div
                        v-if="columnLeft"
                        class="relative group/column"
                        :style="`width:${form.styles[columnLeft.id]?.width}`"
                        @click.stop="select(columnLeft.id)"
                        :class="[
                            {
                            'border-blue-500': selectedBlockId === columnLeft.id,
                            'border-transparent': selectedBlockId !== columnLeft.id
                            },
                            'cursor-pointer border hover:border-stone-400 '
                        ]"
                    >
                        <div 
                            class="h-full flex flex-col justify-center"
                            :style="form.styles[columnLeft.id]"
                        >
                            <component
                                v-for="block in getChildrenOfColumn(columnLeft.id)"
                                :key="block.id"
                                :is="block.type === 'image' ? BlockImage : BlockText"
                                :block="block"
                                @select="select(block.id)"
                                @add="add(block.id)"
                                @remove="remove(block.id)"
                            />
                        </div>

                        <button
                            class="absolute opacity-0 group-hover/column:opacity-100 top-[50%] -right-2.5 -translate-y-1/2 py-1 cursor-ew-resize rounded-sm text-white bg-stone-400 focus-bg-blue-500"
                            @mousedown="startColumnResize(columnLeft.id)"
                        >
                            <Icon class="size-4" name="GripVertical" />
                        </button>
                    </div>

                    <!-- Colonne droite -->
                    <div
                        v-if="columnRight"
                        class="relative group/column"
                        :style="`width:${form.styles[columnRight.id]?.width}`"
                        @click.stop="select(columnRight.id)"
                        :class="[
                            {
                            'border-blue-500': selectedBlockId === columnRight.id,
                            'border-transparent': selectedBlockId !== columnRight.id
                            },
                            'cursor-pointer border hover:border-stone-400 '
                        ]"
                    >
                        <div 
                            class="h-full flex flex-col justify-center"
                            :style="form.styles[columnRight.id]"
                        >
                            <component
                                v-for="block in getChildrenOfColumn(columnRight.id)"
                                :key="block.id"
                                :is="block.type === 'image' ? BlockImage : BlockText"
                                :block="block"
                                @select="select(block.id)"
                                @add="add(block.id)"
                                @remove="remove(block.id)"
                            />
                        </div>

                        <button
                            class="absolute opacity-0 group-hover/column:opacity-100 top-[50%] -right-2.5 -translate-y-1/2 py-1 cursor-ew-resize rounded-sm text-white bg-stone-400 focus-bg-blue-500"
                            @mousedown="startColumnResize(columnRight.id)"
                        >
                            <Icon class="size-4" name="GripVertical" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, inject, computed, onBeforeUnmount } from 'vue';
import Icon from '../../../icon/Icon.vue';
import BlockImage from './components/BlockImage.vue';
import BlockText from './components/BlockText.vue';

const resizingColumnId = ref(null);

const form = inject('form');
const selectedBlockId = inject('selectedBlockId');
const setSelectedBlockId = inject('setSelectedBlockId');

const select = (id) => {
    setSelectedBlockId(id);
};

const add = (id) => {
    const block = form.blocks.find(b => b.id === id);
    const newBlock = {
        id: 'b_' + Math.random().toString(36).slice(2, 11),
        type: 'text',
        parent: block.parent,
        value: 'Nouveau texte'
    };
    form.blocks.push(newBlock);
    form.styles[newBlock.id] = {
        fontSize: '13px',
        color: '#000',
    };
    setSelectedBlockId(newBlock.id);
};

const remove = (id) => {
    const index = form.blocks.findIndex(b => b.id === id);
    if (index !== -1) {
        form.blocks.splice(index, 1);
    }

    if (selectedBlockId.value === id) {
        setSelectedBlockId(null);
    }
};

const columnBlocks = computed(() =>
    form.blocks.filter(b => b.type === 'column')
);
const columnLeft = computed(() =>
    columnBlocks.value.find(col => col.position === 'left')
);
const columnRight = computed(() =>
    columnBlocks.value.find(col => col.position === 'right')
);

const getChildrenOfColumn = (columnId) => {
    return form.blocks.filter(b => b.parent === columnId);
};

const startColumnResize = (columnId) => {
    resizingColumnId.value = columnId;
    document.addEventListener('mousemove', handleColumnResize);
    document.addEventListener('mouseup', stopColumnResize);
};

const handleColumnResize = (event) => {
    if (!resizingColumnId.value) {
        return;
    }

    const container = document.querySelector('.editable-signature-inside');
    const rect = container.getBoundingClientRect();
    const min = 50;
    const max = 300;

    const isLeft = resizingColumnId.value === columnLeft.value?.id;

    let newWidth;

    if (isLeft) {
        newWidth = event.clientX - rect.left;
    } else {
        const leftColumnWidth = parseInt(form.styles[columnLeft.value?.id]?.width || '0', 10);
        newWidth = event.clientX - rect.left - leftColumnWidth;
    }

    const clampedWidth = Math.max(min, Math.min(max, newWidth));
    form.styles[resizingColumnId.value].width = `${clampedWidth}px`;

    form.styles['container'].width = `${parseInt(form.styles[columnLeft.value?.id]?.width) + parseInt(form.styles[columnRight.value?.id]?.width)}px`;
};

const stopColumnResize = () => {
    resizingColumnId.value = null;
    document.removeEventListener('mousemove', handleColumnResize);
    document.removeEventListener('mouseup', stopColumnResize);
};

onBeforeUnmount(() => {
    stopColumnResize();
});
</script>