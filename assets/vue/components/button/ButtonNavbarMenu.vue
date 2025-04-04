<template>
    <a 
        type="button"
        class="block py-2 px-3 text-stone-900 hover:text-stone-600 dark:text-stone-50 dark:hover:text-stone-300"
        :href="url"
    >
        <slot />
    </a>
</template>

<script setup>
const props = defineProps({
    url: {
        type: String,
        default: ''
    }
})
</script>