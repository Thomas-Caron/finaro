<template>
    <div class="flex flex-col">
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

        <select
            :id="id"
            :class="[
                {
                    'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 dark:bg-red-900 focus:border-red-500 dark:text-red-500 dark:placeholder-red-500 dark:border-red-500': isError,
                    'bg-stone-50/30 border border-stone-300 text-stone-900 focus:ring-stone-400 focus:border-stone-400 dark:bg-stone-800/30 dark:border-stone-500 dark:placeholder-stone-400 dark:text-stone-50 dark:focus:ring-stone-400 dark:focus:border-stone-400': !isError,
                },
                'block rounded-lg w-full px-2.5 py-[calc(var(--spacing)*2.1)] text-sm focus:outline-none'
            ]"
            :name="name"
            :value="modelValue"
            @change="$emit('update:modelValue', $event.target.value)"
        >
            <option v-if="placeholder" disabled value>{{ placeholder }}</option>
            <option
                v-for="(item, index) in items"
                :key="index"
                :value="item.value"
                :selected="false"
            >
                {{ item.label }}
            </option>
        </select>
    </div>
</template>

<script setup>
const props = defineProps({
    id: { type: String, required: true },
    name: { type: String, required: true },
    placeholder: { type: String, required: false },
    label: { type: String, required: false },
    items: { type: Array, default: () => ([]) },
    modelValue: { type: [String, Number], default: '' },
    isError: { type: Boolean, default: false },
    required: { type: Boolean, default: false }
});
</script>