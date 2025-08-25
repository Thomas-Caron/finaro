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
        <Breadcrumb :breadcrumbs="props.breadcrumbs" />
    </div>

    <div
        v-if="props.navDate && !props.initialize"
        :class="[
            {
                'ml-20': !sidebar.isSmallScreen && sidebar.isCollapsed,
                'ml-64': !sidebar.isSmallScreen && !sidebar.isCollapsed,
                'ml-0': sidebar.isSmallScreen
            },
            'mt-4'
        ]"
    >
        <NavbarDate
            :api="props.api"
            @update="$emit('update-date', $event)"
        />
    </div>

    <div
        v-if="!props.initialize && props.monthExist"
        :class="[
            {
                'ml-20': !sidebar.isSmallScreen && sidebar.isCollapsed,
                'ml-64': !sidebar.isSmallScreen && !sidebar.isCollapsed,
                'ml-0': sidebar.isSmallScreen
            },
            'p-6 pb-0'
        ]"
    >
        <NavbarAmount
            :api="props.api"
        />
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
import { inject, provide } from 'vue';
import Breadcrumb from '../breadcrumb/Breadcrumb.vue';
import ButtonToggleSidebar from '../../button/ButtonToggleSidebar.vue';
import NavbarAmount from '../navbar/NavbarAmount.vue';
import NavbarDate from '../navbar/NavbarDate.vue';

import { useSidebarStore } from '../../../stores/useSidebarStore';

const sidebar = useSidebarStore();

defineOptions({ inheritAttrs: false });

const props = defineProps({
    api: { type: Object, required: false },
    breadcrumbs: { type: Array, required: true },
    navDate: { type: Boolean, default: false },
    initialize: { type: Boolean, required: false },
    monthExist: { type: Boolean, default: false }
});

const movementType = inject('movementType', null);
const dataCalculatedRemaining = inject('dataCalculatedRemaining', null);

provide('movementType', movementType);
provide('dataCalculatedRemaining', dataCalculatedRemaining);
</script>