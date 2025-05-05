<template>
    <li>
        <button 
            type="button"
            class="flex items-center w-full p-2 text-base text-stone-700 transition duration-75 rounded-lg cursor-pointer group hover:bg-stone-200/30 dark:text-stone-400 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300"
            :aria-controls="`dropdown-${data.name.slug}`"
            :data-collapse-toggle="`dropdown-collapse-${data.name.slug}`"
            :data-tooltip-target="`tooltip-${data.name.slug}`"
            data-tooltip-placement="right"
            :data-dropdown-toggle="`dropdown-${data.name.slug}`"
            data-dropdown-placement="right"
            @click="toggleDropdown()"
        >
            <span class="w-10 h-10 flex justify-center items-center">
                <Icon class="size-6" :name="data.icon" />
            </span>
            <span v-if="!isCollapsed" class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ data.name.text }}</span>
            <span v-if="!isCollapsed">
                <Icon v-if="dropdown" class="size-5" name="ChevronDown" />
                <Icon v-else class="size-5" name="ChevronRight" />
            </span>
        </button>
        <Tooltip v-show="isCollapsed" :id="`tooltip-${data.name.slug}`">
            {{ data.name.text }}
        </Tooltip>
        <div v-show="isCollapsed" :id="`dropdown-${data.name.slug}`" class="absolute hidden w-max bg-stone-100 border border-stone-200 rounded-lg shadow-md dark:border-stone-700 dark:bg-stone-800">
            <ul class="flex flex-col space-y-4 p-4">
                <li v-for="subName in data.subName" :key="subName.slug" class="relative">
                    <a 
                        :href="subName.url"
                        :class="[
                            {
                                'text-stone-400 dark:text-stone-700': subName.badge, 
                                'text-stone-700 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-300': !subName.badge
                            },
                            'inline-block w-full p-2 transition duration-75 rounded-lg hover:bg-stone-200/30 dark:hover:bg-stone-700/40'
                        ]"
                        @click="subName.badge ? $event.preventDefault() : sidebar.closeOnMobile()"
                    >
                        {{ subName.text }}
                    </a>
                    <Badge v-if="subName.badge">{{ subName.badge }}</Badge>
                </li>
            </ul>
        </div>
        <div v-show="!isCollapsed" :id="`dropdown-collapse-${data.name.slug}`" class="hidden ms-6 border-l border-stone-400">
            <ul class="ml-3 space-y-2">
                <li v-for="subName in data.subName" :key="subName.slug" class="relative">
                    <a 
                        :href="subName.url"
                        :class="[
                            {
                                'text-stone-400 dark:text-stone-700': subName.badge, 
                                'text-stone-700 dark:text-stone-400 hover:text-stone-900 dark:hover:text-stone-300': !subName.badge
                            },
                            'flex w-full p-2 transition duration-75 rounded-lg hover:bg-stone-200/30 dark:hover:bg-stone-700/40'
                        ]"
                        @click="subName.badge ? $event.preventDefault() : sidebar.closeOnMobile()"
                    >
                        {{ subName.text }}
                    </a>
                    <Badge v-if="subName.badge">{{ subName.badge }}</Badge>
                </li>
            </ul>
        </div>
    </li>
</template>

<script setup>
import { ref } from 'vue';
import Badge from '../../badge/Badge.vue';
import Icon from '../../icon/Icon.vue';
import Tooltip from '../../tootlip/Tooltip.vue';

import { useSidebarStore } from '../../../stores/useSidebarStore.js';

const sidebar = useSidebarStore();

const props = defineProps({
    data: { type: Object, default: () => ({}) },
    isCollapsed: { type: Boolean, default: false },
});

const dropdown = ref(false);

const toggleDropdown = () => {
    dropdown.value = !dropdown.value;
};
</script>