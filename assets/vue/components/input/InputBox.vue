<template>
    <div>
        <label 
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
        </label>

        <div class="flex">
            <div class="flex-1">
                <input
                    :type="props.type"
                    class="block w-full px-1 py-2 text-sm text-center border rounded-l-lg bg-stone-50 dark:bg-stone-800 border-stone-300 dark:border-stone-600 text-stone-900 dark:text-stone-50 focus:ring-stone-400 focus:border-stone-400 focus:outline-none"
                    :value="props.modelValue.top"
                    min="0"
                    :required="props.required"
                    :disabled="props.disabled"
                    @input="update('top', $event.target.value)"
                />
                <p class="text-xs text-center text-stone-500">Haut</p>
            </div>
            <div class="flex-1">
                <input
                    :type="props.type"
                    class="block w-full px-1 py-2 text-sm text-center border bg-stone-50 dark:bg-stone-800 border-stone-300 dark:border-stone-600 text-stone-900 dark:text-stone-50 focus:ring-stone-400 focus:border-stone-400 focus:outline-none"
                    :value="props.modelValue.right"
                    min="0"
                    :required="props.required"
                    :disabled="props.disabled"
                    @input="update('right', $event.target.value)"
                />
                <p class="text-xs text-center text-stone-500">Droite</p>
            </div>
            <div class="flex-1">
                <input
                    :type="props.type"
                    class="block w-full px-1 py-2 text-sm text-center border bg-stone-50 dark:bg-stone-800 border-stone-300 dark:border-stone-600 text-stone-900 dark:text-stone-50 focus:ring-stone-400 focus:border-stone-400 focus:outline-none"
                    :value="props.modelValue.bottom"
                    min="0"
                    :required="props.required"
                    :disabled="props.disabled"
                    @input="update('bottom', $event.target.value)"
                />
                <p class="text-xs text-center text-stone-500">Bas</p>
            </div>
            <div class="flex-1">
                <input
                    :type="props.type"
                    class="block w-full px-1 py-2 text-sm text-center border rounded-r-lg bg-stone-50 dark:bg-stone-800 border-stone-300 dark:border-stone-600 text-stone-900 dark:text-stone-50 focus:ring-stone-400 focus:border-stone-400 focus:outline-none"
                    :value="props.modelValue.left"
                    min="0"
                    :required="props.required"
                    :disabled="props.disabled"
                    @input="update('left', $event.target.value)"
                />
                <p class="text-xs text-center text-stone-500">Gauche</p>
            </div>
        </div>

        <Alert
            v-if="isError && errorMessage"
            type="error"
            class="mt-3"
        >
            {{ errorMessage }}
        </Alert>
    </div>
</template>

<script setup>
import Alert from '../alert/Alert.vue';

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    label: { type: String, required: false },
    type: { type: String, default: 'number' },
    modelValue: { type: Object, default: () => ({ top: '', right: '', bottom: '', left: '' }) },
    disabled: { type: Boolean, default: false },
    required: { type: Boolean, default: false },
    isError: { type: Boolean, default: false },
    errorMessage: { type: String, default: '' }
});

const update = (key, value) => {
    emit('update:modelValue', { ...props.modelValue, [key]: value })
};
</script>