<template>
    <div class="rounded-lg bg-stone-50 dark:bg-stone-900 p-3 shadow-sm w-full max-w-2xl">
        <div class="grid grid-cols-7 text-center text-sm font-semibold text-stone-500 mb-2">
            <div v-for="(day, i) in weekDays" :key="i">
                {{ day }}
            </div>
        </div>

        <div class="grid grid-cols-7 gap-1 text-center text-sm">
            <div v-for="n in startDay" :key="'empty-' + n" class="h-10"></div>

            <div
                v-for="day in daysInMonth"
                :key="day"
                :class="[
                    {
                    'bg-stone-300/40 dark:bg-stone-600/40': isToday(day),
                    'hover:bg-stone-300/40 dark:hover:bg-stone-600/40': !isToday(day),
                    },
                    'h-10 w-10 mx-auto relative flex items-center justify-center rounded-lg text-stone-900 dark:text-stone-50 transition-colors cursor-pointer outline-none focus:bg-stone-900 dark:focus:bg-white focus:text-stone-50 dark:focus:text-stone-900',
                ]"
                tabindex="0"    
            >
                <div
                    class="w-full h-full flex justify-center items-center"
                    :data-dropdown-toggle="getEventsForDay(day).length ? `dropdown-calendar-${day}` : null"
                    data-dropdown-placement="bottom"
                >
                    {{ day }}
                    <Badge v-if="getEventsForDay(day).length">{{ getEventsForDay(day).length }}</Badge>
                </div>
                <div
                    v-if="getEventsForDay(day).length"
                    :id="`dropdown-calendar-${day}`"
                    class="absolute flex flex-col items-start hidden z-10 w-max p-2 bg-stone-100 border border-stone-200 rounded-lg shadow-md dark:border-stone-700 dark:bg-stone-800"
                >
                    <span
                        v-for="(event, i) in getEventsForDay(day)"
                        :key="i"
                        class="text-sm text-stone-700 dark:text-stone-100"
                    >
                        {{ event.title }}: {{  event.amount }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { computed, onMounted, nextTick, watch } from 'vue';
import Badge from '../badge/Badge.vue';
import { initDropdowns } from 'flowbite';

const monthNames = [
  'janvier', 'fevrier', 'mars', 'avril', 'mai', 'juin',
  'juillet', 'aout', 'septembre', 'octobre', 'novembre', 'decembre'
];

const weekDays = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
  
const props = defineProps({
    month: { type: String, required: true },
    year: { type: Number, required: true },
    events: { type: Array, default: () => ([]) }
});

const today = new Date();
const isToday = (day) => {
    return (
        day === today.getDate() &&
        monthNumber.value === today.getMonth() + 1 &&
        props.year === today.getFullYear()
    );
};

const getEventsForDay = (day) => {
    const paddedMonth = String(monthNumber.value).padStart(2, '0');
    const paddedDay = String(day).padStart(2, '0');
    const dateStr = `${props.year}-${paddedMonth}-${paddedDay}`;

    return props.events.filter((event) => {
        return event.date === dateStr
    });
};

const monthNumber = computed(() => {
    return monthNames.indexOf(props.month.toLowerCase()) + 1
});
  
const daysInMonth = computed(() => {
    return new Date(props.year, monthNumber.value, 0).getDate();
});
  
const startDay = computed(() => {
    const firstDay = new Date(props.year, monthNumber.value - 1, 1).getDay();
    return firstDay === 0 ? 6 : firstDay - 1;
});

watch(
    () => props.events,
    async (newEvents) => {
        if (newEvents && newEvents.length > 0) {
            await nextTick(() => {
                initDropdowns();
            });
        }
    },
    { immediate: false, deep: true }
);

onMounted(async () => {
    await nextTick(() => {
        initDropdowns();
    });
})
</script>