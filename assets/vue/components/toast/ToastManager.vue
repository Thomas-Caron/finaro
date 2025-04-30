<template>
    <div class="fixed w-full h-full flex flex-col items-end z-50 pointer-events-none">
        <Toast
            v-for="(toast, index) in visibleToasts"
            :key="toast.id"
            :class="[
                {
                    'z-30 bottom-4 scale-100': index === 0,
                    'z-20 bottom-8 scale-90': index === 1,
                    'z-10 bottom-12 scale-80': index === 2,
                },
                'transition-transform duration-300 ease-out transform',
            ]"
            v-bind="toast"
        />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import Toast from './Toast.vue';

const toasts = ref([]);

const visibleToasts = computed(() =>
    toasts.value.slice(-3).reverse()
);

const addToast = (toast) => {
    toast.id = Date.now() + Math.random();
    toasts.value.push(toast);
    setTimeout(() => {
        toast.visible = false;
        setTimeout(() => {
            toasts.value = toasts.value.filter(t => t.id !== toast.id);
        }, 500);
    }, toast.duration || 3000);
};

defineExpose({ addToast });
</script>