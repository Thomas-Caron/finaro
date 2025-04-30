<template>
    <div
        role="alert"
        :class="[
            {
                'bg-red-100 text-red-800 dark:bg-red-400 dark:text-red-800': type === 'error',
                'bg-green-100 text-green-800 dark:bg-green-400 dark:text-green-800': type === 'success'
            },
            'p-4 text-sm flex flex-row border rounded-lg'
        ]"
    >
        <Icon class="me-2 size-5" :name="getIcon" />
        <slot />
    </div>
</template>

<script setup>
import { computed } from 'vue';
import Icon from '../icon/Icon.vue';

const props = defineProps({
    type: { type: String, default: 'error' }
});

const getIcon = computed(() => {
    return 'error' === props.type ? 'TriangleAlert' : 'CircleCheck';
});
</script>