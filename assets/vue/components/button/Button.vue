<template>
    <button 
        :type="type"
        :disabled="disabled"
        :class="[
            {
                'text-stone-50 bg-stone-700 hover:bg-stone-800 dark:bg-stone-600 dark:hover:bg-stone-700': 'dark' === color,
                'text-stone-950 bg-stone-50 border border-stone-200 hover:bg-stone-200 dark:bg-stone-100 dark:hover:bg-stone-200 dark:border-none': 'light' === color,
                'text-stone-50 bg-gradient-to-br from-purple-500 to-orange-500 hover:from-purple-400 hover:to-orange-400': 'gradient' === color,
                'cursor-not-allowed opacity-50': disabled,
                'cursor-pointer': !disabled,
            },
            'font-medium rounded-full px-5 py-2.5 flex items-center justify-center focus:outline-none focus:ring-4 focus:ring-stone-300 dark:focus:ring-stone-800'
        ]"
    >
        <Icon v-if="loading" class="animate-spin" name="LoaderCircle" />
        <slot v-else />
    </button>
</template>

<script setup>
import Icon from '../Icon.vue';

const props = defineProps({
    type: {
        type: String,
        default: 'button'
    },
    color: {
        type: String,
        default: 'light'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    }
})
</script>