<template>
    <TransitionSlideFade>
        <div
            v-if="visible"
            class="px-4 py-3 rounded-lg shadow-lg text-stone-800 dark:text-stone-100 flex items-center gap-2"
            :class="[
                bgColor,
                positionClasses,
                'w-full max-w-sm'
            ]"
        >
            {{ message }}
        </div>
    </TransitionSlideFade>
</template>
  
<script setup>
import { onMounted, ref, computed } from 'vue';
import TransitionSlideFade from '../transition/TransitionSlideFade.vue';

const props = defineProps({
    message: { type: String, default: '' },
    type: { type: String, default: 'default' },
    duration: { type: Number, default: 3000 },
    position: { type: String, default: 'bottom-right' }
});

const visible = ref(false);

onMounted(() => {
    visible.value = true;
    setTimeout(() => {
        visible.value = false;
    }, props.duration);
});

const bgColor = computed(() => ({
    success: 'bg-green-100 dark:bg-green-800 border border-green-300 dark:border-green-700',
    error: 'bg-red-100 dark:bg-red-800 border border-red-300 dark:border-red-700',
    info: 'bg-blue-100 dark:bg-blue-800 border border-blue-300 dark:border-blue-700',
    default: 'bg-stone-100 dark:bg-stone-800 border border-stone-300 dark:border-stone-700',
})[props.type]);

const positionClasses = computed(() => {
    switch (props.position) {
        case 'top-right':
            return 'fixed top-4 right-4';
        case 'top-left':
            return 'fixed top-4 left-4';
        case 'bottom-right':
            return 'fixed bottom-4 right-4';
        case 'bottom-left':
            return 'fixed bottom-4 left-4';
        default:
            return 'fixed bottom-4 right-4';
    }
});
</script>