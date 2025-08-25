<template>
    <ContainerApp
        :breadcrumbs="breadcrumbs"
    >
        <div class="flex justify-end mb-3">
            <button
                type="button"
                class="flex flex-row items-center text-sm text-stone-400 hover:text-stone-800 dark:hover:text-stone-100 hover:underline transition cursor-pointer"
                data-modal-target="modal-add-labels"
                data-modal-toggle="modal-add-labels"
            >
                <Icon class="size-4 me-1" name="Plus" />
                Ajouter
            </button>
        </div>
        <Table
            :columns="[
                { key: 'name', label: 'Nom' },
                { key: 'color', label: 'Couleur' },
                { key: 'updatedAt', label: 'Modifié le' },
                { key: 'createdAt', label: 'Créé le' }
            ]"
            :rows="data"
            :update="{
                fields: [
                    { key: 'name', label: 'Nom', input: Input, type: 'text', disabled: (row) => row.slug === 'autre' },
                    { key: 'color', label: 'Couleur', input: ColorPicker }
                ],
                item: data
            }"
            :loading="loading"
            :actions="{
                modify: true,
                delete: (row) => row.slug !== 'autre'
            }"
            @update="handleUpdate"
            @delete="handleDelete"
        />

        <Modal
            ref="modal"
            id="modal-add-labels"
            title="Ajouter une catégorie"
            @close="close"
        >
            <template #body>
                <InputRepeater
                    id="modal-form-labels"
                    class="mb-5"
                    :fields="[
                        { key: 'name', label: 'Nom', input: Input, type: 'text' },
                        { key: 'color', label: 'Couleur', input: ColorPicker },
                    ]"
                    v-model:items="formData.collection"
                />
            </template>
            <template #footer>
                <Button
                    class="w-full"
                    color="gradient"
                    type="button"
                    :loading="loading"
                    :disabled="loading"
                    @click="handleSubmit"
                >
                    Ajouter
                </Button>
            </template>
        </Modal>
    </ContainerApp>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import Button from '../../../components/button/Button.vue';
import ColorPicker from '../../../components/input/ColorPicker.vue';
import ContainerApp from '../../../components/layout/container/ContainerApp.vue';
import Icon from '../../../components/icon/Icon.vue';
import Input from '../../../components/input/Input.vue';
import InputRepeater from '../../../components/input/InputRepeater.vue';
import Modal from '../../../components/modal/Modal.vue';
import Table from '../../../components/table/Table.vue';

import useApi from '../../../composables/useApi';
import useDateFormat from '../../../composables/useDateFormat';
import { useToast } from '../../../plugins/useToast';

const { get, post, put, del } = useApi();
const { toFullDateFormatted } = useDateFormat();
const toast = useToast();

const props = defineProps({
    url: { type: Object, default: () => ({}) },
    api: { type: Object, default: () => ({}) }
});

const breadcrumbs = computed(() => {
    return [
        { text: 'Catégories', url: props.url.label },
    ];
});

const modal = ref(null);
const loading = ref(false);
const data = ref([]);

const createDefaultData = () => ({
    name: '',
    color: ''
});

const formData = reactive({
    collection: [createDefaultData()]
});

const getLabels = async () => {
    try {
        loading.value = true;
        const response = await get(props.api.getLabels);

        if (response.success) {
            data.value = response.data.map(item => {
                return {
                    id: item.id,
                    name: item.name,
                    slug: item.slug,
                    color: item.color,
                    updatedAt: toFullDateFormatted(item.updatedAt),
                    createdAt: toFullDateFormatted(item.createdAt)
                };
            });
            loading.value = false;
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleSubmit = async () => {
    const emptyField = formData.collection.find(item => !item.name?.trim() || !item.color?.trim());
    if (emptyField) {
        if (!emptyField.name?.trim()) {
            toast.error('❌ Le nom ne peut pas être vide');
        } else if (!emptyField.color?.trim()) {
            toast.error('❌ La couleur ne peut pas être vide');
        }
        return;
    }

    try {
        loading.value = true;
        const response = await post(props.api.getLabels, formData);

        if (response.success) {
            close();
            loading.value = false;
            await getLabels();
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleUpdate = async (data) => {
    const emptyField = !data.name?.trim() || !data.color?.trim();
    if (emptyField) {
        if (!emptyField.name?.trim()) {
            toast.error('❌ Le nom ne peut pas être vide');
        } else if (!emptyField.color?.trim()) {
            toast.error('❌ La couleur ne peut pas être vide');
        }
        return;
    }

    try {
        const response = await put(props.api.update.replace('id', data.id), data);

        if (response.success) {
            await getLabels();
            toast.default('🎉 Modifié avec succès !');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleDelete = async (id) => {
    try {
        const response = await del(props.api.delete.replace('id', id));

        if (response.success) {
            await getLabels();
            toast.default('🎉 Supprimé avec succès !');
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const close = () => {
    formData.collection = [createDefaultData()];
    modal.value.close();
};

onMounted(async () => {
    await getLabels();
});
</script>