<template>
    <button 
        :type="type"
        :disabled="disabled"
        :class="`font-medium rounded-full text-sm px-5 py-2.5 text-center focus:outline-none focus:ring-4 focus:ring-stone-300 dark:focus:ring-stone-800 ${
            color === 'dark' 
            ? 'text-stone-50 bg-stone-700 hover:bg-stone-800 dark:bg-stone-600 dark:hover:bg-stone-700' 
            : color === 'light' 
            ? 'text-stone-950 bg-stone-50 border border-stone-200 hover:bg-stone-200 dark:bg-stone-100 dark:hover:bg-stone-200 dark:border-none'
            : color === 'gradient' 
            ? 'text-stone-50 bg-gradient-to-br from-purple-500 to-orange-500 hover:from-purple-400 hover:to-orange-400'
            : ''
        }`"
    >
        <slot />
    </button>
</template>

<script setup>
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
    }
})
</script>