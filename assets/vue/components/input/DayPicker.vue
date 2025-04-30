<template>
    <div>
        <p 
            v-if="label"
            :class="[
                {
                    'text-red-700 dark:text-red-500': isError,
                    'text-stone-900 dark:text-stone-50': !isError
                },
                'block mb-2 text-sm font-medium'
            ]"
        >
            {{ label }}<span v-if="required" class="text-red-500 ms-2">*</span>
        </p>

        <div class="flex">
            <button
                type="button"
                :class="[
                    {
                        'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-red-900 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500': isError,
                        'bg-stone-50/30 border border-stone-300 text-stone-900 focus:ring-stone-400 focus:border-stone-400 dark:bg-stone-800/30 dark:border-stone-500 dark:placeholder-stone-400 dark:text-stone-50 dark:focus:ring-stone-400 dark:focus:border-stone-400': !isError,
                        'rounded-none rounded-s-lg': !sidebarStore.isSmallScreen,
                        'rounded-lg': sidebarStore.isSmallScreen,
                    },
                    'block w-full px-4 py-2 text-sm text-left truncate hover:bg-stone-300/30 dark:hover:bg-stone-700/30 cursor-pointer focus:outline-none'
                ]"
                :data-dropdown-toggle="`dropdown-day-picker-${id}`"
                data-dropdown-placement="bottom"
            >
                {{ modelValue ? `${modelValue} du mois` : 'Choisir un jour' }}
            </button>
            <span v-if="!sidebarStore.isSmallScreen" class="inline-flex items-center px-3 text-sm text-stone-800 bg-stone-300 border rounded-s-0 border-stone-300 border-s-0 rounded-e-md dark:bg-stone-500 dark:text-stone-100 dark:border-stone-500">
                <Icon class="size-4" name="Calendar" />
            </span>
        </div>

        <Alert
            v-if="isError && errorMessage"
            type="error"
            class="mt-3"
        >
            {{  errorMessage }}
        </Alert>

        <div
            :id="`dropdown-day-picker-${id}`"
            class="absolute flex flex-col items-start hidden z-10 w-max p-2 bg-stone-100 border border-stone-200 rounded-lg shadow-md dark:border-stone-700 dark:bg-stone-800"
        >
            <div class="grid grid-cols-7 gap-1">
                <button
                    v-for="day in 30"
                    :key="day"
                    @click="selectDay(day)"
                    type="button"
                    :class="[
                        {
                            'bg-stone-900 dark:bg-white text-stone-50 dark:text-stone-900': modelValue === day,
                            'text-stone-900 dark:text-stone-50 hover:bg-stone-300/40 dark:hover:bg-stone-600/40': modelValue !== day,
                        },
                        'h-8 w-8 mx-auto text-sm relative flex items-center justify-center rounded-lg transition cursor-pointer outline-none',
                    ]"
                >
                    {{ day }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import Alert from '../alert/Alert.vue';
import Icon from '../icon/Icon.vue';

import { useSidebarStore } from '../../stores/useSidebarStore';

const sidebarStore = useSidebarStore();

const props = defineProps({
    id: { type: String, required: true },
    name: { type: String, required: true },
    label: { type: String, required: false },
    modelValue: { type: Number, default: 1 },
    required: { type: Boolean, default: false },
    isError: { type: Boolean, default: false },
    errorMessage: { type: String, default: '' }
});

const emit = defineEmits(['update:modelValue']);

const selectDay = (day) => {
    emit('update:modelValue', day);
    document.activeElement.blur();
    document.body.click();
};
</script>