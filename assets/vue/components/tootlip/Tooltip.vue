<template>
    <div :id="id" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white dark:text-stone-950 transition-opacity duration-300 bg-stone-950 rounded-lg shadow-xs opacity-0 tooltip dark:bg-stone-50">
        <slot />
    </div>
</template>

<script setup>
const props = defineProps({
    id: {
        type: String,
        required: true
    }
});
</script>