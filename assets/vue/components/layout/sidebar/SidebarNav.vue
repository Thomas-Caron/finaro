<template>
    <ul class="flex-1 px-3 py-6 space-y-2 font-medium">
        
        <SidebarNavItem
            :data="nav.dashboard"
            :is-collapsed="isCollapsed"
        />

        <SidebarNavDropdown
            :data="nav.account"
            :is-collapsed="isCollapsed"
        />

        <SidebarNavDropdown
            :data="nav.simulator"
            :is-collapsed="isCollapsed"
        />
    </ul>
</template>

<script setup>
import SidebarNavDropdown from './SidebarNavDropdown.vue';
import SidebarNavItem from './SidebarNavItem.vue';

const props = defineProps({
    isCollapsed: { type: Boolean, default: false },
    nav: { type: Object, default: () => ({}) }
});
</script>