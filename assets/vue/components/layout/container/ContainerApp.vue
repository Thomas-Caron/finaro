<template>
    <div
        :class="[
            {
                'ml-20': !sidebar.isSmallScreen && sidebar.isCollapsed,
                'ml-64': !sidebar.isSmallScreen && !sidebar.isCollapsed,
                'ml-0': sidebar.isSmallScreen
            },
            'p-6 pb-0 flex flex-row'
        ]"
    >
        <div
            v-if="sidebar.isSmallScreen && sidebar.isMobileOpen"
            class="fixed inset-0 bg-black/80 z-30 sm:hidden"
            @click="sidebar.toggle"
        ></div>

        <ButtonToggleSidebar />
        <Breadcrumb :breadcrumbs="breadcrumbs" />
    </div>

    <div
        :class="[
            {
                'ml-20': !sidebar.isSmallScreen && sidebar.isCollapsed,
                'ml-64': !sidebar.isSmallScreen && !sidebar.isCollapsed,
                'ml-0': sidebar.isSmallScreen
            },
            'p-6',
            $attrs.class
        ]"
    >
        <slot />
    </div>
</template>

<script setup>
import Breadcrumb from '../breadcrumb/Breadcrumb.vue';
import ButtonToggleSidebar from '../../button/ButtonToggleSidebar.vue';

import { useSidebarStore } from '../../../stores/useSidebarStore';

const sidebar = useSidebarStore();

const props = defineProps({
    breadcrumbs: { type: Array, required: true }
});
</script>