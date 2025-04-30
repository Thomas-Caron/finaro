<template>
    <div :id="id" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full h-full md:inset-0 bg-stone-800/30 dark:bg-stone-400/30 backdrop-blur-sm">
        
        <div class="relative p-4 w-full max-w-2xl">
            <div class="relative bg-stone-50 rounded-lg shadow-sm dark:bg-stone-900">

                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-stone-200 dark:border-stone-600">
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-stone-50">
                        {{ title }}
                    </h3>
                    <button 
                        type="button"
                        class="text-stone-400 bg-transparent hover:bg-stone-200 hover:text-stone-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-stone-600 dark:hover:text-stone-50 cursor-pointer"
                        :data-modal-hide="id"
                        aria-label="Close modal"
                        @click="$emit('close')"
                    >
                        <Icon class="size-5" name="X" />
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <div class="p-4 max-h-[50vh] overflow-y-auto">
                    <slot name="body" />
                </div>

                <div v-if="$slots.footer" class="flex items-center justify-center p-4 border-t border-stone-200 rounded-b dark:border-stone-600">
                    <slot name="footer" />
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import Icon from '../icon/Icon.vue';

const props = defineProps({
    id: { type: String, required: true },
    title: { type: String, required: true }
});

const close = () => {
    const closeButton = document.querySelector(`[data-modal-hide="${props.id}"]`);
    if (closeButton) {
        closeButton.click();
    }
};

defineExpose({ close });
</script>