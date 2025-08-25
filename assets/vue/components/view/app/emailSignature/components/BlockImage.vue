<template>
     <!-- Colonne image -->
    <div :style="form.styles[props.block.id].width">
        <div
            v-if="props.block"
            :class="[
                {
                    'border-blue-500': selectedBlockId === props.block.id,
                    'border-transparent': selectedBlockId !== props.block.id
                },
                'field field-image cursor-pointer border hover:border-stone-400'
    ]       "
            @click.stop="$emit('select')"
        >
            <img :src="props.block.value" :style="form.styles[props.block.id]" />
        </div>
    </div>
</template>

<script setup>
import { inject } from 'vue';

const emits = defineEmits(['select']);

const props = defineProps({
    block: { type: Object, required: true },
});

const form = inject('form');
const selectedBlockId = inject('selectedBlockId');
</script>