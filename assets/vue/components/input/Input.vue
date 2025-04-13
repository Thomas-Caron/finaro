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
        <input 
            :id="id"
            :type="type"
            :class="[
                {
                    'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-stone-700 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500': isError,
                    'bg-stone-50 border border-stone-300 text-stone-900 focus:ring-stone-400 focus:border-stone-400 dark:bg-stone-700 dark:border-stone-500 dark:placeholder-stone-400 dark:text-stone-50 dark:focus:ring-stone-400 dark:focus:border-stone-400': !isError
                },
                'block w-full p-2.5 text-sm rounded-lg'
            ]"
            :name="name"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :required="required"
            :autofocus="autofocus"
        >
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

const props = defineProps({
    id: {
        type: String,
        required: true
    },
    name: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: false
    },
    type: {
        type: String,
        default: 'text'
    },
    modelValue: {
        type: [String, Number],
        default: ''
    },
    required: {
        type: Boolean,
        default: false
    },
    autofocus: {
        type: Boolean,
        default: false
    },
    isError: {
        type: Boolean,
        default: false
    },
    errorMessage: {
        type: String,
        default: ''
    }
});
</script>