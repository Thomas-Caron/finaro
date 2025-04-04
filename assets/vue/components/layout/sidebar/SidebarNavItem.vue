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
        <div v-show="isCollapsed" :id="`tooltip-${data.name.slug}`" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
            {{ data.name.text }}
        </div>
    </li>
</template>

<script setup>
import Icon from '../../Icon.vue';

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