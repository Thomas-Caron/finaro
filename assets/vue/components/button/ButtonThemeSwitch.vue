<template>
    <label class="relative inline-flex items-center cursor-pointer" data-tooltip-target="tooltip-theme" data-tooltip-placement="bottom">
        <input ref="checkbox" type="checkbox" value="" class="sr-only peer" @click="toggleTheme" />
        <span class="sr-only">Switch theme light/dark</span>

        <span class="w-14 h-7 rounded-full border border-stone-300 dark:border-stone-400 transition-colors bg-stone-200 dark:bg-stone-500 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-stone-100 dark:peer-focus:ring-stone-400/20 peer-checked:bg-stone-200 dark:peer-checked:bg-stone-600"></span>

        <Icon name="Sun" class="absolute rounded-full left-0.5 top-0.5 size-6 p-1 text-stone-400 dark:text-stone-600 bg-stone-50 dark:bg-stone-400 transition-transform duration-300 peer-checked:translate-x-7 peer-checked:opacity-0" />
        <Icon name="Moon" class="absolute rounded-full left-0.5 top-0.5 size-6 p-1 text-stone-400 dark:text-stone-600 bg-stone-50 dark:bg-stone-400 transition-transform duration-300 opacity-0 peer-checked:translate-x-7 peer-checked:opacity-100" />
    </label>
    <Tooltip id="tooltip-theme" :class="tooltipClass">Thème clair/sombre</Tooltip>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Icon from '../icon/Icon.vue';
import Tooltip from '../tootlip/Tooltip.vue';

import useTheme from '../../composables/useTheme';

const props = defineProps({
    tooltipClass: { type: String, required: false }
});

const { toggleTheme, initTheme, isDark } = useTheme();
const checkbox = ref(null);

onMounted(() => {
    initTheme();
    checkbox.value.checked = isDark.value;
});
</script>