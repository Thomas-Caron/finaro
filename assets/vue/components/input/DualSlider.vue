<template>
    <div>
        <label
            v-if="label"
            :class="[
                {
                    'mb-6': labelLeft && labelRight,
                    'mb-2': !labelLeft && !labelRight,
                    'text-red-700 dark:text-red-500': isError,
                    'text-stone-900 dark:text-stone-50': !isError
                },
                'block text-sm font-medium'
            ]"
        >
            {{ label }}
            <span v-if="required" class="text-red-500 ms-2">*</span>
        </label>

        <div class="relative w-full pb-8">
            <div v-if="labelLeft && labelRight" class="absolute -top-4 left-0 w-full flex justify-between text-sm text-stone-500 dark:text-stone-400">
                <span>{{ labelLeft }}</span>
                <span>{{ labelRight }}</span>
            </div>

            <input
                :id="id"
                ref="input"
                type="range"
                class="w-full h-2 rounded-lg appearance-none cursor-pointer"
                :style="{ background: inputBackground }"
                :name="name"
                :min="0"
                :max="100"
                :step="1"
                :value="modelValue"
                @input="$emit('update:modelValue', +$event.target.value)"
                @change="$emit('change', +$event.target.value)"
            />

            <div class="absolute top-6 left-0 w-full flex justify-between text-sm text-black dark:text-white">
                <span class="px-2 py-1 rounded-md bg-stone-300/30 dark:bg-stone-700/40">{{ getUnitLeft }}</span>
                <span class="px-2 py-1 rounded-md bg-stone-300/30 dark:bg-stone-700/40">{{ getUnitRight }}</span>
            </div>
        </div>

        <Alert v-if="isError && errorMessage" type="error" class="mt-3">
            {{ errorMessage }}
        </Alert>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import Alert from '../alert/Alert.vue';

import useConvertFilter from '../../composables/useConvertFilter.js';
import useTheme from '../../composables/useTheme.js';

const { getCurrency, getPercentage, getYear, formatNumber } = useConvertFilter();
const { isDark } = useTheme();

const props = defineProps({
    id: { type: String, required: true },
    name: { type: String, required: true },
    label: { type: String, required: false },
    labelLeft: { type: String, required: false },
    labelRight: { type: String, required: false },
    min: { type: Number, default: 0 },
    max: { type: Number, default: 100 },
    step: { type: Number, default: 1 },
    unit: { type: String, default: null },
    modelValue: { type: [Number, String], default: 50 },
    required: { type: Boolean, default: false },
    isError: { type: Boolean, default: false },
    errorMessage: { type: String, default: '' }
});

const input = ref(null);

const getFormattedValue = (value) => {
    switch (props.unit) {
        case 'currency':
            return getCurrency(value);
        case 'percentage':
            return getPercentage(value);
        case 'year':
            return getYear(value);
        case 'month':
            return getMonth(value);
        default:
            return formatNumber(value, 0);
    }
};

const getUnitLeft = computed(() => getFormattedValue(props.max - props.modelValue));
const getUnitRight = computed(() => getFormattedValue(props.modelValue));

const inputBackground = computed(() => {
    const percent = (props.modelValue - props.min) / (props.max - props.min) * 100;
    const colorEnd = isDark.value ? '#44403c' : '#a8a29e';
    const colorStart = isDark.value ? '#a8a29e' : '#44403c';


    return `linear-gradient(to right, 
        ${colorStart} 0%,
        ${colorStart} ${percent}%,
        ${colorEnd} ${percent}%,
        ${colorEnd} 100%)`;
});
</script>