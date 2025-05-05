<template>
    <div
        v-if="loading"
        class="absolute inset-0 z-10 bg-white/70 dark:bg-stone-700/50 backdrop-blur-sm flex items-center justify-center rounded-lg"
    >
        <Icon v-if="loading" class="animate-spin w-1/3 h-1/3 text-stone-700 dark:text-stone-300" name="LoaderCircle" />
    </div>
</template>

<script setup>
import Icon from '../icon/Icon.vue';

const props = defineProps({
    loading: { type: Boolean, default: false }
});
</script>