<template>
    <li>
        <a 
            :href="data.name.url"
            class="flex items-center p-2 text-stone-700 rounded-lg dark:text-stone-400 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 group"
            :data-tooltip-target="`tooltip-${data.name.slug}`"
            data-tooltip-placement="right"
        >
            
            <span class="w-10 h-10 flex justify-center items-center">
                <Icon class="size-6" :name="data.icon" />
            </span>
            <span v-if="!isCollapsed" class="flex-1 ms-3 whitespace-nowrap">{{ data.name.text }}</span>
        </a>
        <Tooltip v-show="isCollapsed" :id="`tooltip-${data.name.slug}`">
            {{ data.name.text }}
        </Tooltip>
    </li>
</template>

<script setup>
import Icon from '../../icon/Icon.vue';
import Tooltip from '../../tootlip/Tooltip.vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({
            name: {
                slug: '',
                text: '',
                url: ''
            },
            icon: ''
        })
    },
    isCollapsed: {
        type: Boolean,
        default: false
    },
});
</script>