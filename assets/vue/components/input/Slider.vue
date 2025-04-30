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

        <div class="relative w-full pb-8">
            <input 
                :id="id"
                ref="input"
                type="range"
                class="w-full h-2 rounded-lg appearance-none cursor-pointer"
                :style="{ background: inputBackground }"
                :name="name"
                :min="min"
                :max="max"
                :step="step"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                @change="$emit('change', $event.target.value)"
            />

            <div 
                ref="containerUnit"
                class="absolute top-6 left-0 whitespace-nowrap w-max transform bg-stone-300/30 dark:bg-stone-700/40 text-sm px-2 py-1 rounded-md text-black dark:text-white"
                :style="{ left: `${containerUnitLeft}px` }"
            >
                <span>
                    {{  getUnit }}
                </span>
            </div>
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
import { ref, computed, nextTick, watch, onMounted, onBeforeUnmount } from 'vue';
import Alert from '../alert/Alert.vue';

import useConvertFilter from '../../composables/useConvertFilter.js';
import useTheme from '../../composables/useTheme.js';

const { getCurrency, getPercentage, getYear, getMonth, formatNumber } = useConvertFilter();
const { isDark } = useTheme();

const props = defineProps({
    id: { type: String, required: true },
    name: { type: String, required: true },
    label: { type: String, required: false },
    min: { type: Number, default: 0 },
    max: { type: Number, default: 9999 },
    step: { type: Number, default: 1 },
    unit: { type: String, default: null },
    modelValue: { type: [Number, String], default: 0 },
    required: { type: Boolean, default: false },
    isError: { type: Boolean, default: false },
    errorMessage: { type: String, default: '' }
});

const input = ref(null);
const containerUnit = ref(null);
const containerUnitLeft = ref(0);

const getUnit = computed(() => {
    switch (props.unit) {
        case 'currency':
            return getCurrency(props.modelValue);
        case 'percentage':
            return getPercentage(props.modelValue);
        case 'year':
            return getYear(props.modelValue);
        case 'month':
            return getMonth(props.modelValue);
        default:
            return formatNumber(props.modelValue, 0);
    };
});

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

const resizeThumb = () => {
    if (!input.value || !containerUnit.value) return;

    const min = parseFloat(props.min);
    const max = parseFloat(props.max);
    const percent = (props.modelValue - min) / (max - min);

    const thumbSize = 20;
    const inputWidth = input.value.clientWidth - thumbSize;
    const thumbPosition = percent * inputWidth + thumbSize / 2;
    const spanWidth = containerUnit.value.offsetWidth;

    containerUnitLeft.value = thumbPosition - spanWidth / 2;
};

onMounted(() => {
    nextTick(resizeThumb);
    window.addEventListener('resize', resizeThumb);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', resizeThumb);
});

watch(() => props.modelValue, () => {
    nextTick(resizeThumb);
});
</script>