<template>
    <div v-click-outside="closeAccordion" class="w-full rounded-lg shadow-sm bg-stone-50 dark:bg-stone-900 border border-stone-200 dark:border-stone-700">
        <div
            class="text-stone-500 dark:text-stone-400"
            data-accordion="collapse"
            data-active-classes="rounded-t-lg bg-stone-200 dark:bg-stone-700 text-stone-700 dark:text-stone-200"
            data-inactive-classes="transparent text-stone-500 dark:text-stone-400"
        >
            <div :id="`accordion-title-${id}`">
                <button 
                    ref="accordion"
                    type="button"
                    class="w-full p-2 flex items-center justify-between cursor-pointer"
                    :data-accordion-target="`#accordion-body-${id}`"
                    aria-expanded="false"
                    :aria-controls="`accordion-body-${id}`"
                >
                    <slot name="header"></slot>
                    <Icon data-accordion-icon name="ChevronDown" class="size-5 rotate-180" aria-hidden="true" />
                </button>
            </div>

            <div :id="`accordion-body-${id}`" class="hidden" :aria-labelledby="`accordion-title-${id}`">
                <div class="p-2 border-t border-b border-stone-200 dark:border-stone-700">
                    <slot name="body"></slot>
                </div>

                <div class="p-2">
                    <slot name="actions"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import Icon from '../icon/Icon.vue';
import { initAccordions } from 'flowbite';

import clickOutside from '../../directives/clickOutsideUp';

defineOptions({
    directives: {
        'click-outside': clickOutside
    }
});

const props = defineProps({
    id: { type: [String, Number], required: true }
});

const accordion = ref(null);

const closeAccordion = () => {
    if (!accordion.value) return;

    const isOpen = accordion.value.getAttribute('aria-expanded') === 'true';

    if (isOpen) {
        accordion.value?.click();
    }
};

onMounted(async () => {
    await nextTick(() => {
        initAccordions();
    });
});
</script>
