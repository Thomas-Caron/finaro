<template>
    <aside
        id="sidebar"
        :class="[
            {
                'w-64': !isCollapsed,
                'w-20': isCollapsed
            },
            'fixed top-0 left-0 z-40 h-svh transition-[width] duration-300 bg-stone-50 border-r border-stone-200 dark:bg-stone-900 dark:border-stone-700'
        ]"
        aria-label="Sidebar"
    >
        <div class="flex flex-col h-full overflow-y-auto bg-stone-50 dark:bg-stone-900 divide-y divide-stone-200 dark:divide-stone-700">

            <button
                type="button"
                :class="[
                    {
                        'cursor-e-resize': isCollapsed,
                        'cursor-w-resize': !isCollapsed
                    },
                    'absolute w-1 top-0 -right-1 h-full bg-transparent hover:bg-stone-200 dark:hover:bg-stone-700'
                ]"
                @click="toggleSidebar"
            >
                <span class="sr-only">Toggle sidebar</span>
            </button>

            <a 
                href="/" 
                :class="[
                    {
                        'ps-8': !isCollapsed,
                        'justify-center': isCollapsed
                    },
                    'flex py-6'
                ]"
            >
                <span class="text-2xl font-semibold text-stone-900 whitespace-nowrap dark:text-stone-50">
                    {{ 
                        isCollapsed
                        ? 'F.'
                        : 'Finaro'
                    }}
                </span>
            </a>

            <SidebarNav :is-collapsed="isCollapsed" />

            <SidebarUser :is-collapsed="isCollapsed" :user="user" />
        </div>
    </aside>

    <div
        :class="[
            {
                'ml-64': !isCollapsed,
                'ml-20': isCollapsed
            },
            'p-6 flex flex-row'
        ]"
    >
        <button
            type="button"
            class="rounded-lg p-2 hover:bg-stone-100 dark:hover:bg-stone-600 cursor-pointer"
            @click="toggleSidebar"
        >
            <span class="sr-only">Toggle sidebar</span>
            <Icon class="size-6 text-stone-700 dark:text-stone-300" name="PanelLeft" />
        </button>

        <Breadcrumb />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Breadcrumb from '../breadcrumb/Breadcrumb.vue';
import Icon from '../../Icon.vue';
import SidebarNav from './SidebarNav.vue';
import SidebarUser from './SidebarUser.vue';

const props = defineProps({
    user: {
        type: Object,
        default: () => ({
            firstname: 'test',
            lastname: 'test',
            email: 'test@test.com'
        })
    }
});

const isCollapsed = ref(false);

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
};
</script>
