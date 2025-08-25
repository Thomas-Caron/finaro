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

        <div class="h-[38px] w-9">
            <button
                :style="{ backgroundColor: modelValue }"
                class="w-full h-full rounded-lg border border-stone-300 dark:border-stone-500 cursor-pointer"
                :data-dropdown-toggle="`dropdown-color-picker-${id}`"
                data-dropdown-placement="bottom"
            ></button>

            <div
                :id="`dropdown-color-picker-${id}`"
                class="absolute mt-2 flex flex-col hidden items-start z-10 w-max p-2 bg-stone-100 border border-stone-200 rounded-lg shadow-md dark:border-stone-700 dark:bg-stone-800"
            >
                <div class="grid grid-cols-4 gap-2">
                    <div
                        v-for="color in palette"
                        :key="color"
                        :style="{ backgroundColor: color }"
                        @click="selectColor(color)"
                        class="w-8 h-8 rounded-lg cursor-pointer border-2 border-stone-300 dark-border-stone-700"
                        :class="modelValue === color ? 'ring-2 ring-stone-400 dark:ring-stone-600' : 'hover:ring-2 hover:ring-stone-400 dark:hover:ring-stone-600'"
                    ></div>
                </div>

                <div v-if="props.customColor" class="mt-2">
                    <div
                        class="w-8 h-8 flex justify-center items-center rounded-lg cursor-pointer border-2 border-stone-300 dark-border-stone-700 hover:ring-2 hover:ring-stone-400 dark:hover:ring-stone-600"
                        @click="triggerCustomColor"
                    >
                        <Icon class="size-5 text-stone-300 dark-text-stone-700 " name="Plus" />
                    </div>
                    <input
                        type="color"
                        ref="customColorInput"
                        class="absolute opacity-0"
                        @change="onCustomColorChange"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Icon from '../icon/Icon';

const emit = defineEmits(['update:modelValue']);

const customColorInput = ref(null);

const props = defineProps({
    id: { type: String, required: true },
    name: { type: String, required: true },
    label: { type: String, required: false },
    modelValue: { type: String, default: '' },
    required: { type: Boolean, default: false },
    isError: { type: Boolean, default: false },
    errorMessage: { type: String, default: '' },
    customColor: { type: Boolean, default: true }
});

const palette = [
    '#FFB3BA', // rouge pastel
    '#FFCCE5', // rose pastel
    '#FFD1B2', // orange pastel
    '#FFE0CC', // pêche rosée
    '#FFF5BA', // jaune pastel
    '#D0F0C0', // vert pastel
    '#B2F0E3', // turquoise pastel
    '#BAE1FF', // bleu pastel
    '#A0B9D9', // bleu marine pastel
    '#D7B2FF', // violet pastel
    '#E0C8FF', // lilas pastel
    '#A3A3A3', // gris pastel
];

const selectColor = (color) => {
    emit('update:modelValue', color);
    document.activeElement.blur();
    document.body.click();
};

const triggerCustomColor = () => {
    customColorInput.value?.click();
};

const onCustomColorChange = (event) => {
    const color = event.target.value;
    emit('update:modelValue', color);
};
</script>