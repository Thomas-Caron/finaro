<template>
    <button
        type="button"
        class="rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-600 cursor-pointer"
        @click="sidebar.toggle()"
    >
        <span class="sr-only">Toggle sidebar</span>
        <Icon class="size-6 text-stone-700 dark:text-stone-300" name="PanelLeft" />
    </button>
</template>

<script setup>
import Icon from '../icon/Icon.vue';

import { useSidebarStore } from '../../stores/useSidebarStore';

const sidebar = useSidebarStore();
</script>