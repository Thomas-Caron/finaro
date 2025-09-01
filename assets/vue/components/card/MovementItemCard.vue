<template>
    <div
        ref="swipe"
        v-click-outside="() => { isSwiped = false }"
        class="rounded-lg p-2 relative flex flex-wrap items-center overflow-sm-hidden bg-stone-50 dark:bg-stone-900 border border-stone-200 dark:border-stone-700 group"
    >
        <button
            v-if="actions.move"
            type="button"
            class="handle w-0 opacity-0 scale-95 flex cursor-grab text-stone-500 dark:text-stone-400 hover:text-stone-700 dark:hover:text-stone-300 transition-all duration-300 ease-in-out group-hover:w-6 group-hover:opacity-100 group-hover:pointer-events-auto group-hover:scale-100"
        >
            <Icon class="size-5 -ml-1" name="GripVertical" />
        </button>

        <div class="flex flex-col flex-1 min-w-0 pe-4 text-stone-500 dark:text-stone-400">
            <input
                class="font-bold truncate appearance-none outline-none border-none"
                type="text"
                placeholder="Nom"
                :disabled="!actions.name"
                @blur="updateName"
                v-model="data.name"
            />
            <select
                v-if="data.label"
                class="italic truncate appearance-none outline-none border-none cursor-pointer"
                :style="`color: ${data.label.color}`"
                v-model="data.label"
                @change="updateLabel"
            >
                <option
                    v-for="(item, index) in labels"
                    :key="index"
                    :value="item"
                >
                    {{ item.name }}
                </option>
            </select>
        </div>

        <div 
            :class="[
                {
                    '-translate-x-28': isSwiped
                },
                'h-full flex flex-row transition-transform duration-300'
            ]"
            >
            <div
                :class="[
                    'h-full flex flex-col items-end pe-4',
                    data.isCharged !== undefined && data.isCharged !== null ? 'justify-between' : 'justify-center'
                ]"
            >
                <button
                    type="button"
                    class="cursor-pointer mt-1"
                    @click="updateTransactionDirection"
                >
                    <Icon v-if="data.transactionDirection === 'debit'"class="size-4 text-rose-500 hover:text-rose-500/70" name="TrendingDown"/>
                    <Icon v-if="data.transactionDirection === 'credit'"class="size-4 text-serene hover:text-serene/70" name="TrendingUp"/>
                </button>

                <button
                    v-if="data.formatDay"
                    type="button"
                    class="cursor-pointer text-sm text-stone-500 dark:text-stone-400 hover:text-stone-700 dark:hover:text-stone-300"
                    :data-dropdown-toggle="`dropdown-day-picker-${data.id}`"
                    data-dropdown-placement="bottom"
                >
                    {{ toDayMonth(data.formatDay) }}
                </button>
                <div
                    :id="`dropdown-day-picker-${data.id}`"
                    class="absolute flex flex-col items-start hidden z-10 w-max p-2 bg-stone-100 border border-stone-200 rounded-lg shadow-md dark:border-stone-700 dark:bg-stone-800"
                >
                    <div class="grid grid-cols-7 gap-1">
                        <button
                            v-for="day in 30"
                            :key="day"
                            @click="updateDay(day)"
                            type="button"
                            :class="[
                                {
                                    'bg-stone-900 dark:bg-white text-stone-50 dark:text-stone-900': data.day === day,
                                    'text-stone-900 dark:text-stone-50 hover:bg-stone-300/40 dark:hover:bg-stone-600/40': data.day !== day,
                                },
                                'h-8 w-8 mx-auto text-sm relative flex items-center justify-center rounded-lg transition cursor-pointer outline-none',
                            ]"
                        >
                            {{ day }}
                        </button>
                    </div>
                </div>
            </div>

            <div
                :class="[
                    'h-full flex flex-col items-end',
                    data.isCharged !== undefined && data.isCharged !== null ? 'justify-between' : 'justify-center'
                ]"
            >
                <div  @click="enableAmountEditing($event)">
                    <div v-if="!readonlyAmount">
                        <input
                            ref="amountRef"
                            :class="[
                                data.transactionDirection === 'debit' ? 'text-stone-500 dark:text-stone-400' : 'text-serene',
                                'appearance-none outline-none border-none text-right no-spinner'
                            ]"
                            :style="{ width: `${inputWidth}px` }"
                            type="number"
                            min="0"
                            v-model="data.amount"
                            @blur="disableAmountEditing"
                            @input="updateWidth"
                        />
                        <span :class="[data.transactionDirection === 'debit' ? 'text-stone-500 dark:text-stone-400' : 'text-serene', 'ml-[0.5px]']">
                            €
                        </span>
                    </div>
                    <div v-else :class="data.transactionDirection === 'debit' ? 'text-stone-500 dark:text-stone-400' : 'text-serene'">
                        {{ data.transactionDirection === 'debit' ? '-' : '' }} {{ getCurrency(data.amount) }}
                    </div>
                    <span ref="amountSizer" class="invisible absolute text-base">
                        {{ data.amount }}
                    </span>
                </div>

                <button 
                    v-if="actions.charge"
                    type="button"
                    :class="[
                        {
                            'bg-serene text-stone-900 hover:bg-serene/70': data.isCharged,
                            'text-stone-500 bg-stone-200 dark:text-stone-400 dark:bg-stone-700 hover:bg-stone-500/30 dark:hover:bg-stone-200/30': !data.isCharged
                        },
                        'py-1 px-2 -mb-1 text-xs font-bold rounded-full cursor-pointer w-fit transition duration-300'
                    ]"
                    @click="updateCharge"
                >
                    {{ data.transactionDirection === 'debit' ? 'Prélevé' : 'Reçu' }}
                </button>
            </div>
        </div>

        <button
            v-if="actions.remove"
            type="button"
            class="absolute pointer-events-none opacity-0 scale-95 inline-flex items-center justify-center w-5 h-5 rounded-full -top-2 -end-2 cursor-pointer text-stone-500 dark:text-stone-400 hover:text-stone-700 dark:hover:text-stone-300 bg-stone-50 dark:bg-stone-900 border border-stone-200 dark:border-stone-700 transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:pointer-events-auto group-hover:scale-100"
            v-bind="getModalAttributes(id, data.id)"
            @click.stop="remove"
        >
            <Icon class="size-3" name="X" />
        </button>
        <button
            v-if="actions.remove"
            type="button"
            :class="[
                {
                    'opacity-0 pointer-events-none': !isSwiped,
                    'opacity-100': isSwiped
                },
                'absolute right-0 h-full rounded-lg bg-rose-500 text-white flex items-center justify-center px-4 cursor-pointer transition-opacity duration-300'
            ]"
            v-bind="getModalAttributes(id, data.id)"
            @click.stop="remove"
        >
            Supprimer
        </button>
        <ModalDelete
            v-if="actions.remove && actions.removeConfirm"
            :id="`modal-remove-${id}-${data.id}`"
            title="Supprimer"
            @delete="emit('delete', data.id)"
        />
    </div>
</template>

<script setup>
import { ref, nextTick, onMounted, watch } from 'vue';
import { useSwipe } from '@vueuse/core';
import Icon from '../icon/Icon.vue';
import ModalDelete from '../modal/ModalDelete.vue';
import { initDropdowns, initModals } from 'flowbite';

import useConvertFilter from '../../composables/useConvertFilter';
import useDateFormat from '../../composables/useDateFormat';
import { useToast } from '../../plugins/useToast';
import clickOutside from '../../directives/clickOutside';

const { getCurrency } = useConvertFilter();
const { toDayMonth } = useDateFormat();
const toast = useToast();

defineOptions({
    directives: {
        'click-outside': clickOutside
    }
});

const emit = defineEmits(['update', 'delete']);

const initialName = ref('');
const amountRef = ref(null);
const readonlyAmount = ref(true);
const initialAmount = ref(0);
const amountSizer = ref(null);
const inputWidth = ref(40);
const swipe = ref(null);
const isSwiped = ref(false);

const { isSwiping, direction, lengthX } = useSwipe(swipe, { threshold: 10 });

const props = defineProps({
    id: { type: String, required: true },
    data: { type: Object, required: true },
    labels: { type: Array, required: false },
    actions: { type: Object, default: () => ({}) }
});

const updateCharge = () => {
    props.data.isCharged = !props.data.isCharged;
    emit('update', {
        ...props.data,
        label: props.data.label?.id
    });
};

const updateDay = (day) => {
    if (props.data.day !== day) {
        props.data.day = day;
        const date = new Date(props.data.formatDay);
        props.data.formatDay = new Date(Date.UTC(date.getFullYear(), date.getMonth(), day)).toISOString();
        emit('update', {
            ...props.data,
            label: props.data.label?.id
        });
    }
    document.activeElement.blur();
    document.body.click();
};

const updateLabel = () => {
    props.data.label = props.labels.find(l => l.id === props.data.label.id);
    emit('update', {
        ...props.data,
        label: props.data.label?.id
    });
};

const updateTransactionDirection = () => {
    props.data.transactionDirection = props.data.transactionDirection === 'credit' ? 'debit' : 'credit';
    emit('update', {
        ...props.data,
        label: props.data.label?.id
    });
};

const updateName = () => {
    if (props.data.name === initialName.value) return;
    if (props.data.name.trim() === '') {
        toast.error('❌ Le nom ne peut pas être vide');
        props.data.name = initialName.value;
        return;
    }

    initialName.value = props.data.name;

    emit('update', {
        ...props.data,
        label: props.data.label?.id
    });
};

const remove = () => {
    isSwiped.value = false;
    !props.actions.removeConfirm ? emit('delete') : '';
};

const enableAmountEditing = () => {
    updateWidth();
    initialAmount.value = props.data.amount;
    readonlyAmount.value = false;

    nextTick(() => {
        const input = amountRef.value;
        input.focus();
    });
};

const disableAmountEditing = () => {
    readonlyAmount.value = true;
    if (props.data.amount === initialAmount.value) return;
    if (props.data.amount === null || props.data.amount === '' || isNaN(props.data.amount) || props.data.amount < 0) {
        toast.error('❌ Le montant est invalide');
        props.data.amount = initialalAmount.value;
        return;
    }

    emit('update', {
        ...props.data,
        label: props.data.label?.id
    });
};

const getModalAttributes = (id, dataId) => {
    if (props.actions.removeConfirm) {
        return {
            'data-modal-target': `modal-remove-${id}-${dataId}`,
            'data-modal-toggle': `modal-remove-${id}-${dataId}`,
        };
    }

    return {};
};

const updateWidth = () => {
    nextTick(() => {
        if (amountSizer.value) {
            const width = amountSizer.value.offsetWidth;
            inputWidth.value = width;
        }
    });
};

watch(() => props.data, async () => {
    await nextTick(() => {
        initDropdowns();
        initModals();
    });
}, { deep: true });

watch([direction, lengthX, isSwiping], () => {
    if (isSwiping.value) {
        if (
            props.actions.remove &&
            direction.value === 'left' &&
            Math.abs(lengthX.value) > 40
        ) {
            isSwiped.value = true;
        }
        if (direction.value === 'right' && Math.abs(lengthX.value) > 10) isSwiped.value = false;
    }
});

onMounted(async () => {
    initialName.value = props.data.name;
    initialAmount.value = props.data.amount;
    await nextTick(() => {
        initDropdowns();
        initModals();
    });
});
</script>