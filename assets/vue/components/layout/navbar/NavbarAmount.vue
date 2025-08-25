<template>
    <div class="px-2 w-full rounded-lg bg-stone-50 border border-stone-200 dark:bg-stone-900 dark:border-stone-700">
        <div class="flex items-center justify-between h-full">
            <button
                :class="[
                    {
                        'border-b-2 border-stone-400': movementType === 'expense'
                    },
                    'h-full p-2 cursor-pointer group'
                ]"
                @click="movementType = 'expense'"
            >
                <div class="flex flex-row items-center text-sm text-stone-400 dark:text-stone-600 group-hover:text-stone-300 group-hover:dark:text-stone-500 transition duration-300">
                    <Icon class="size-4 mr-2" name="TrendingDown" />Dépenses
                </div>
                <div class="text-center text-stone-600 dark:text-stone-300">{{ getCurrency(dataCalculatedRemaining.totalExpenses) }}</div>
            </button>

            <button
                :class="[
                    {
                        'border-b-2 border-stone-400': movementType === 'income'
                    },
                    'h-full p-2 cursor-pointer group'
                ]"
                @click="movementType = 'income'"
            >
                <div class="flex flex-row items-center text-sm text-stone-400 dark:text-stone-600 group-hover:text-stone-300 group-hover:dark:text-stone-500 transition duration-300">
                    <Icon class="size-4 mr-2" name="TrendingUp" />Revenus
                </div>
                <div class="text-center text-stone-600 dark:text-stone-300">{{ getCurrency(dataCalculatedRemaining.totalIncomes) }}</div>
            </button>

            <div
                class="shrink-0 h-full p-2"
            >
                <div class="flex flex-row items-center text-sm text-stone-400 dark:text-stone-600">
                    <Icon class="size-4 mr-2" name="Scale" />Restant estimé
                </div>
                <div class="text-center text-stone-600 dark:text-stone-300">{{ getCurrency(dataCalculatedRemaining.totalRemaining) }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { inject } from 'vue';
import Icon from '../../icon/Icon.vue';

import useConvertFilter from '../../../composables/useConvertFilter';

const { getCurrency } = useConvertFilter();

const movementType = inject('movementType');
const dataCalculatedRemaining = inject('dataCalculatedRemaining');
</script>


