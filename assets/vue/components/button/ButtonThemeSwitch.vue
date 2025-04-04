<template>
    <label class="relative inline-flex items-center cursor-pointer">
        <input ref="checkbox" type="checkbox" value="" class="sr-only peer" @click="toggle" />
        <span class="sr-only">Switch theme light/dark</span>

        <span class="w-14 h-7 rounded-full border border-stone-300 dark:border-stone-400 transition-colors bg-stone-200 dark:bg-stone-500 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-stone-100 dark:peer-focus:ring-stone-400/20 peer-checked:bg-stone-200 dark:peer-checked:bg-stone-600"></span>

        <Icon name="Sun" class="absolute rounded-full left-0.5 top-0.5 size-4 p-1 text-stone-400 dark:text-stone-600 bg-stone-50 dark:bg-stone-400 transition-transform duration-300 peer-checked:translate-x-7 peer-checked:opacity-0" />
        <Icon name="Moon" class="absolute rounded-full left-0.5 top-0.5 size-4 p-1 text-stone-400 dark:text-stone-600 bg-stone-50 dark:bg-stone-400 transition-transform duration-300 opacity-0 peer-checked:translate-x-7 peer-checked:opacity-100" />
    </label>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Icon from '../Icon.vue';

const checkbox = ref(null);

onMounted(() => {
    const theme = localStorage.getItem('theme');

    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        if (checkbox.value) {
            checkbox.value.checked = true;
        }
    } else {
        document.documentElement.classList.remove('dark');
        if (checkbox.value) {
            checkbox.value.checked = false;
        }
    }
});

const toggle = () => {
    const html = document.documentElement;

    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
};
</script>