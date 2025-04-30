<template>
    <div>
        <label 
            v-if="label"
            :for="id"
            :class="[
                {
                    'text-red-700 dark:text-red-500': isError,
                    'text-stone-900 dark:text-stone-50': !isError
                },
                'block mb-2 text-sm font-medium'
            ]"
        >
            {{ label }}<span v-if="required" class="text-red-500 ms-2">*</span>
        </label>

        <div class="flex">
            <span v-if="icon && 'left' === icon.direction && !sidebarStore.isSmallScreen" class="inline-flex items-center px-3 text-sm text-stone-800 bg-stone-300 border rounded-e-0 border-stone-300 border-e-0 rounded-s-md dark:bg-stone-500 dark:text-stone-100 dark:border-stone-500">
                <Icon class="size-4" :name="icon.name" />
            </span>

            <input 
                :id="id"
                :type="type"
                :class="[
                    {
                        'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-red-900 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500': isError,
                        'bg-stone-50/30 border border-stone-300 text-stone-900 focus:ring-stone-400 focus:border-stone-400 dark:bg-stone-800/30 dark:border-stone-500 dark:placeholder-stone-400 dark:text-stone-50 dark:focus:ring-stone-400 dark:focus:border-stone-400': !isError,
                        'rounded-none': icon && !sidebarStore.isSmallScreen,
                        'rounded-lg': !icon || sidebarStore.isSmallScreen,
                        'rounded-e-lg': icon && 'left' === icon.direction && !sidebarStore.isSmallScreen,
                        'rounded-s-lg': icon && 'right' === icon.direction && !sidebarStore.isSmallScreen,
                    },
                    'block w-full px-4 py-2 text-sm focus:outline-none'
                ]"
                :name="name"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                @change="$emit('change', $event.target.value)"
                :required="required"
                :autofocus="autofocus"
            />

            <span v-if="icon && 'right' === icon.direction && !sidebarStore.isSmallScreen" class="inline-flex items-center px-3 text-sm text-stone-800 bg-stone-300 border rounded-s-0 border-stone-300 border-s-0 rounded-e-md dark:bg-stone-500 dark:text-stone-100 dark:border-stone-500">
                <Icon class="size-4" :name="icon.name" />
            </span>
        </div>

        <Alert
            v-if="isError && errorMessage"
            type="error"
            class="mt-3"
        >
            {{  errorMessage }}
        </Alert>
    </div>
</template>

<script setup>
import Alert from '../alert/Alert.vue';
import Icon from '../icon/Icon.vue';

import { useSidebarStore } from '../../stores/useSidebarStore.js';

const sidebarStore = useSidebarStore();

const props = defineProps({
    id: { type: String, required: true },
    name: { type: String, required: true },
    label: { type: String, required: false },
    type: { type: String, default: 'text' },
    modelValue: { type: [String, Number], default: '' },
    icon: { type: Object, required: false },
    required: { type: Boolean, default: false },
    autofocus: { type: Boolean, default: false },
    isError: { type: Boolean, default: false },
    errorMessage: { type: String, default: '' }
});
</script>