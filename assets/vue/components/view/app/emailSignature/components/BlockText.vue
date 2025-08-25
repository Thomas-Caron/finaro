<template>
    <!-- Colonne texte -->
    <div
        :class="[
            {
                'border-blue-500': selectedBlockId === props.block.id,
                'border-transparent': selectedBlockId !== props.block.id
            },
            'line cursor-pointer border hover:border-stone-400'
        ]"
        @click.stop="$emit('select')"
    >
        <div
            :class="`relative field field-${props.block.type} group/field`"
            :style="form.styles[props.block.id]"
        >
            <button
                class="absolute top-1/2 right-0 -translate-y-1/2 pe-1 opacity-0 group-hover/field:opacity-100 cursor-pointer"
                @click.stop="$emit('remove')"
            >
                <Icon name="Trash" class="text-red-500 size-3" />
            </button>
            {{ props.block.label || props.block.value }}
            <div class="overflow-hidden transition-all duration-200 ease-in-out h-0 opacity-0 group-hover/field:h-5 group-hover/field:opacity-100">
                <button
                    class="text-xs font-normal text-stone-400 hover:text-stone-600 cursor-pointer"
                    @click.stop="$emit('add')"
                >
                    + Ajouter un bloc
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { inject } from 'vue';
import Icon from '../../../../icon/Icon.vue';

const emits = defineEmits(['select', 'add', 'remove']);

const props = defineProps({
    block: { type: Object, required: true },
});

const form = inject('form');
const selectedBlockId = inject('selectedBlockId');
</script>