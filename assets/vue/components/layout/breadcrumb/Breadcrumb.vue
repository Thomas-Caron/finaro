<template>
    <nav class="flex items-center ml-4 space-x-6">
        <template v-for="(item, index) in breadcrumbs" :key="index">
            <component
                :is="item.url ? 'a' : 'span'"
                :href="item.url || null"
                :class="[
                    {
                        'pl-6 first:border-l first:border-stone-300': 0 === index,
                        'text-stone-400 dark:text-stone-600 hover:text-stone-700 dark:hover:text-stone-300': index !== breadcrumbs.length - 1,
                        'text-stone-700 dark:text-stone-300': index === breadcrumbs.length - 1
                    },
                    'transtion-all duration-300 ease-in-all'
                ]"
            >
                {{ item.text }}
            </component>

            <Icon v-if="index < breadcrumbs.length - 1" class="size-5 text-stone-400 dark:text-stone-600" name="ChevronRight" />
        </template>
    </nav>
</template>

<script setup>
import Icon from '../../icon/Icon.vue';

const props = defineProps({
    breadcrumbs: { type: Array, required: true }
});
</script>