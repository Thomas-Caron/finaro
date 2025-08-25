<template>
    <div class="shadow-sm rounded-lg flex flex-col items-center justify-center p-4 text-stone-500 dark:text-stone-400 bg-stone-50 dark:bg-stone-900">
        <p>Aucune donnée disponible pour le mois {{ ['a','e','i','o','u','y'].includes(formDataDate.month.charAt(0)) ? 'd\'' : 'de ' }}{{ getStringMonth(formDataDate.month) }}.</p>
        <Button
            class="mt-4"
            color="gradient"
            data-modal-target="modal-create-month"
            data-modal-toggle="modal-create-month"
        >
            Créer ce mois
        </Button>
        <ModalCreateMonth
            id="modal-create-month"
            :title="`Créer le mois ${['a','e','i','o','u','y'].includes(formDataDate.month.charAt(0)) ? 'd\'' : 'de '}${getStringMonth(formDataDate.month)}`"
            :api="props.api"
        />
    </div>
</template>

<script setup>
import { inject } from 'vue';
import Button from '../button/Button.vue';
import ModalCreateMonth from '../modal/ModalCreateMonth.vue';

import useDateFormat from '../../composables/useDateFormat';

const { getStringMonth } = useDateFormat();

const props = defineProps({
    api: { type: Object, required: true }
});

const formDataDate = inject('formDataDate');
</script>