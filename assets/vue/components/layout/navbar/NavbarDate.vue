<template>
    <div class="h-8 w-full bg-stone-50 border-t border-b border-stone-200 dark:bg-stone-900 dark:border-stone-700">
        <div class="flex items-center justify-between h-full divide-x divide-stone-200 dark:divide-stone-700">
            <button
                v-if="canScrollLeft"
                class="shrink-0 h-full px-2 bg-stone-50 hover:bg-stone-200 dark:bg-stone-900 dark:hover:bg-stone-700 cursor-pointer"
                @click="scrollLeft"
            >
                <Icon class="size-4 text-gray-600 dark:text-stone-300" name="ChevronLeft" />
            </button>

            <div class="flex-1 h-full overflow-hidden">
                <div
                    ref="scrollContainer"
                    class="flex items-center divide-x divide-stone-200 dark:divide-stone-700 h-full overflow-x-auto scrollbar-none transition-all duration-300"
                    @scroll="checkScroll"
                >
                    <button
                        v-for="(month, index) in availableMonths"
                        ref="monthButtons"
                        :key="index"
                        :class="[
                            {
                                'text-stone-50 bg-stone-900 dark:bg-white dark:text-stone-900': formDataDate.month === month.value,
                                'text-stone-900 bg-stone-50 hover:bg-stone-200 dark:text-stone-50 dark:bg-stone-900 dark:hover:bg-stone-700': formDataDate.month !== month.value,
                                'last:border-r last:border-stone-200 dark:last:border-stone-700': !canScrollLeft && !canScrollRight
                            },
                            'text-sm font-medium whitespace-nowrap h-full min-w-15 px-2 truncate cursor-pointer',
                        ]"
                        :title="month.label"
                        @click="formDataDate.month = month.value"
                    >
                        {{ month.label }}
                    </button>
                </div>
            </div>

            <button
                v-if="canScrollRight"
                class="shrink-0 h-full px-2 bg-stone-50 hover:bg-stone-200 dark:bg-stone-900 dark:hover:bg-stone-700 cursor-pointer"
                @click="scrollRight"
            >
                <Icon class="size-4 text-gray-600 dark:text-gray-300" name="ChevronRight" />
            </button>

            <button
                class="h-full min-w-10 flex justify-center items-center text-stone-900 bg-stone-50 hover:bg-stone-200 dark:text-stone-50 dark:bg-stone-900 dark:hover:bg-stone-700 cursor-pointer"
                data-tooltip-target="tooltip-create-new-month"
                data-tooltip-placement="top"
                data-modal-target="modal-create-new-month"
                data-modal-toggle="modal-create-new-month"
            >
                <Icon class="size-4" name="Plus" />
            </button>
            <Tooltip id="tooltip-create-new-month">
                Créer un nouveau mois
            </Tooltip>
            <ModalCreateMonth
                id="modal-create-new-month"
                title="Créer un nouveau mois"
                :api="api"
                :months="unavailableMonths"
            />

            <select
                class="h-full min-w-20 px-2 text-stone-900 bg-stone-50 hover:bg-stone-200 dark:text-stone-50 dark:bg-stone-900 dark:hover:bg-stone-700 focus:outline-none focus:ring-0 focus:border-none cursor-pointer"
                v-model="formDataDate.year"
            >
                <option
                    v-for="(item, index) in years"
                    :key="index"
                    :value="item.value"
                    :selected="false"
                >
                    {{ item.label }}
                </option>
            </select>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, provide } from 'vue';
import Icon from '../../icon/Icon.vue';
import ModalCreateMonth from '../../modal/ModalCreateMonth.vue';
import Tooltip from '../../tootlip/Tooltip.vue';

import useApi from '../../../composables/useApi';
import useCurrentDate from '../../../composables/useCurrentDate';

const { get } = useApi();
const { dateNow } = useCurrentDate();

const emit = defineEmits(['update']);

const props = defineProps({
    api: { type: Object, default: () => ({}) }
});

const availableMonths = ref([]);
const unavailableMonths = ref([]);
const years = ref([]);

const formDataDate = ref(dateNow);

const scrollContainer = ref(null);
const monthButtons = ref([]);
const canScrollLeft = ref(false);
const canScrollRight = ref(false);

provide('formDataDate', formDataDate);

const scrollLeft = () => {
    const firstButton = monthButtons.value[0];
    const scrollAmount = firstButton?.offsetWidth + 8 || 100;
    scrollContainer.value.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
};

const scrollRight = () => {
    const firstButton = monthButtons.value[0];
    const scrollAmount = firstButton?.offsetWidth + 8 || 100;
    scrollContainer.value.scrollBy({ left: scrollAmount, behavior: 'smooth' });
};


const checkScroll = () => {
    const el = scrollContainer.value;
    if (!el) return;

    canScrollLeft.value = el.scrollLeft > 0;
    canScrollRight.value = el.scrollLeft + el.clientWidth < el.scrollWidth;
};

const getAvailableMonths = async () => {
    const response = await get(props.api.getAvailableMonths.replace('year', formDataDate.value.year));

    if (response.success) {
        availableMonths.value = response.data;
    }
};
const getUnavailableMonths = async () => {
    const response = await get(props.api.getUnavailableMonths.replace('year', formDataDate.value.year));

    if (response.success) {
        unavailableMonths.value = response.data;
    }
};

const getYears = async () => {
    const response = await get(props.api.getYears);

    if (response.success) {
        years.value = response.data;
    }
};

watch(formDataDate.value, (newVal) => {
    emit('update', { ...newVal });
}, { deep: true });
watch(() => formDataDate.value.year, async (newVal, oldVal) => {
    if (newVal !== oldVal) {
        await getAvailableMonths();
        await getUnavailableMonths();
    }
});

onMounted(async () => {
    emit('update', { ...formDataDate.value });
    await getAvailableMonths();
    await getUnavailableMonths();
    await getYears();

    monthButtons.value = scrollContainer.value?.querySelectorAll('button');
    window.addEventListener('resize', checkScroll);
    checkScroll();
});
</script>