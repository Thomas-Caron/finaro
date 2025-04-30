<template>
    <aside
        id="sidebar"
        :class="[
            {
                'w-20': sidebar.isCollapsed && !sidebar.isSmallScreen,
                'w-64': !sidebar.isCollapsed && !sidebar.isSmallScreen,
                'translate-x-0 w-64': sidebar.isMobileOpen && sidebar.isSmallScreen,
                '-translate-x-full w-64': !sidebar.isMobileOpen && sidebar.isSmallScreen,

            },
            'fixed top-0 left-0 z-40 h-svh transition-[width, translate] duration-300 ease-in-out bg-stone-50 border-r border-stone-200 dark:bg-stone-900 dark:border-stone-700',
        ]"
        aria-label="Sidebar"
    >
        <div class="flex flex-col h-full overflow-y-auto bg-stone-50 dark:bg-stone-900 divide-y divide-stone-200 dark:divide-stone-700">

            <button
                type="button"
                :class="[
                    {
                        'cursor-e-resize': sidebar.isCollapsed,
                        'cursor-w-resize': !sidebar.isCollapsed
                    },
                    'absolute w-1 top-0 -right-1 h-full bg-transparent hover:bg-stone-200 dark:hover:bg-stone-700',
                ]"
                @click="sidebar.toggle()"
            >
                <span class="sr-only">Toggle sidebar</span>
            </button>

            <a 
                href="/" 
                :class="[
                    {
                        'justify-center': sidebar.isCollapsed,
                        'ps-8': !sidebar.isCollapsed
                    },
                    'flex py-6'
                ]"
            >
                <span class="text-2xl font-semibold text-stone-900 whitespace-nowrap dark:text-stone-50">
                    {{ 
                        sidebar.isCollapsed
                        ? 'F.'
                        : 'Finaro'
                    }}
                </span>
            </a>

            <SidebarNav :is-collapsed="sidebar.isCollapsed" :nav="nav" />

            <SidebarUser :is-collapsed="sidebar.isCollapsed" :user="user" :url="url" />
        </div>
    </aside>
</template>

<script setup>
import SidebarNav from './SidebarNav.vue';
import SidebarUser from './SidebarUser.vue';

import { useSidebarStore } from '../../../stores/useSidebarStore';

const sidebar = useSidebarStore();

const props = defineProps({
    user: { type: Object, default: () => ({}) },
    url: { type: Object, default: () => ({}) },
    nav: { type: Object, default: () => ({}) }
});
</script>
