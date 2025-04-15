<template>
    <div 
        :class="[
            {
                'ml-20': sidebar.isCollapsed,
                'ml-64': !sidebar.isCollapsed
            },
            'relative py-3 z-0 bg-stone-50 dark:bg-stone-900 border-t border-stone-200 dark:border-stone-700'
        ]"
    >
        <span class="block text-sm text-stone-900 text-center dark:text-stone-50">© 2025 - Finaro. Made by <a class="hover:underline" href="https://thomascaron.com" target="_blank">Thomas CARON</a></span>
    </div>
</template>

<script setup>
import { useSidebarStore } from '../../../stores/useSidebarStore';

const sidebar = useSidebarStore();
</script>