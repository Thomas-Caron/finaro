<template>
    <li>
        <button 
            type="button"
            class="flex items-center w-full p-2 text-base text-stone-700 transition duration-75 rounded-lg cursor-pointer group hover:bg-stone-200/30 dark:text-stone-400 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300"
            :aria-controls="`dropdown-${data.name.slug}`"
            :data-collapse-toggle="`dropdown-collapse-${data.name.slug}`"
            :data-tooltip-target="`tooltip-${data.name.slug}`"
            data-tooltip-placement="right"
            :data-dropdown-toggle="`dropdown-${data.name.slug}`"
            data-dropdown-placement="right"
            @click="toggleDropdown()"
        >
            <span class="w-10 h-10 flex justify-center items-center">
                <Icon class="size-6" :name="data.icon" />
            </span>
            <span v-if="!isCollapsed" class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ data.name.text }}</span>
            <span v-if="!isCollapsed">
                <Icon v-if="dropdown" class="size-5" name="ChevronDown" />
                <Icon v-else class="size-5" name="ChevronRight" />
            </span>
        </button>
        <div v-show="isCollapsed" :id="`tooltip-${data.name.slug}`" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
            {{ data.name.text }}
        </div>
        <div v-show="isCollapsed" :id="`dropdown-${data.name.slug}`" class="absolute hidden w-max bg-white border border-gray-100 rounded-lg shadow-md dark:border-gray-700 dark:bg-gray-700">
            <ul class="flex flex-col space-y-4 p-4">
                <li v-for="subName in data.subName" :key="subName.slug">
                    <a :href="subName.url" class="inline-block w-full p-2 text-stone-700 transition duration-75 rounded-lg hover:bg-stone-200/30 dark:text-stone-400 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300">
                        {{ subName.text }}
                    </a>
                </li>
            </ul>
        </div>
        <div v-show="!isCollapsed" :id="`dropdown-collapse-${data.name.slug}`" class="hidden ms-6 border-l border-stone-400">
            <ul class="ml-3 space-y-2">
                <li v-for="subName in data.subName" :key="subName.slug">
                    <a :href="subName.url" class="flex w-full p-2 text-stone-700 transition duration-75 rounded-lg group hover:bg-stone-200/30 dark:text-stone-400 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300">
                        {{ subName.text }}
                    </a>
                </li>
            </ul>
        </div>
    </li>
</template>

<script setup>
import { ref } from 'vue';
import Icon from '../../Icon.vue';

const props = defineProps({
    data: {
        type: Object,
        default: () => ({
            name: {
                slug: '',
                text: '',
            },
            subName: [
                {
                    slug: '',
                    text: '',
                    url: ''
                },
                {
                    slug: '',
                    text: '',
                    url: ''
                }
            ],
            icon: '',

        })
    },
    isCollapsed: {
        type: Boolean,
        default: false
    },
});


const dropdown = ref(false);

const toggleDropdown = () => {
    dropdown.value = !dropdown.value;
};
</script>