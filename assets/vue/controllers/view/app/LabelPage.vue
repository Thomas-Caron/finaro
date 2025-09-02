<template>
    <Loader v-if="loading" :loading="true" />
    <ContainerApp
        v-else
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

        <div class="flex gap-4 w-full">
            <div
                v-for="(column, colIndex) in columns"
                :key="colIndex"
                class="flex-1 flex flex-col gap-4"
            >
                <DropdownCard
                    v-for="(data, index) in column"
                    :key="data.id"
                    :id="`label-${data.id}`"
                >
                    <template #header>
                        <div class="h-full flex flex-row items-center gap-2">
                            <div
                                class="rounded-full w-10 h-10 flex items-center justify-center border border-stone-200 dark:border-stone-700"
                                :style="`background-color: ${data.color}`"
                            >
                                <Icon :class="`size-4 ${getContrastColor(data.color)}`" :name="data.icon" />
                            </div>
                            <p :style="`color: ${data.color}`">{{ data.name }}</p>
                        </div>
                    </template>
                    <template #body>
                        <div class="flex flex-col gap-2 text-sm text-stone-500 dark:text-stone-400">
                            <div class="flex flex-row">
                                <p class="w-32">Nom :</p>
                                <p class="flex-1">{{ data.name }}</p>
                            </div>
                            <div class="flex flex-row">
                                <p class="w-32">Couleur :</p>
                                <div class="flex-1">
                                    <span
                                        class="w-6 h-6 rounded-md block border border-stone-300 dark:border-stone-500"
                                        :style="`background-color: ${data.color};`"
                                    ></span>
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <p class="w-32">Icône :</p>
                                <div class="flex-1">
                                    <span
                                        class="w-6 h-6 rounded-md flex items-center justify-center border border-stone-300 dark:border-stone-500"
                                    >
                                        <Icon :name="data.icon" class="size-4 text-stone-500 dark:text-stone-400" />
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <p class="w-32">Modifié le :</p>
                                <p class="flex-1">{{ data.updatedAt }}</p>
                            </div>
                            <div class="flex flex-row">
                                <p class="w-32">Créé le :</p>
                                <p class="flex-1">{{ data.createdAt }}</p>
                            </div>
                        </div>
                    </template>
                    <template #actions>
                        <div class="flex flex-row justify-center">
                            <button
                                type="button"
                                class="flex items-center me-2 text-sm text-stone-500 border border-stone-500 dark:text-stone-400 hover:bg-stone-200/30 dark:border-stone-400 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 rounded-lg p-2 cursor-pointer"
                                :data-modal-target="`modal-modify-label-${data.id}`"
                                :data-modal-toggle="`modal-modify-label-${data.id}`"
                            >
                                <Icon class="size-3 me-1" name="Pen" /> Modifier
                            </button>
                            <button
                                v-if="data.slug !== 'autre'"
                                type="button"
                                class="flex items-center ms-2 text-sm text-red-500 border border-red-500 dark:text-red-400 hover:bg-red-200/30 dark:border-red-400 dark:hover:bg-red-700/40 hover:text-red-900 dark:hover:text-red-300 rounded-lg p-2 cursor-pointer"
                                :data-modal-target="`modal-remove-label-${data.id}`"
                                :data-modal-toggle="`modal-remove-label-${data.id}`"
                            >
                                <Icon class="size-3 me-1" name="Trash" /> Supprimer
                            </button>
                            <ModalUpdate
                                :id="`modal-modify-label-${data.id}`"
                                title="Modifier"
                                :update="{
                                    fields: [
                                        { key: 'name', label: 'Nom', input: Input, type: 'text', disabled: data.slug === 'autre' },
                                        { key: 'color', label: 'Couleur', input: ColorPicker },
                                        { key: 'icon', label: 'Icône', input: IconPicker }
                                    ],
                                    item: data
                                }"
                                @update="(newData) => handleUpdate(newData)"
                            />
                            <ModalDelete
                                v-if="data.slug !== 'autre'"
                                :id="`modal-remove-label-${data.id}`"
                                title="Supprimer"
                                @delete="handleDelete(data.id)"
                            />
                        </div>
                    </template>
                </DropdownCard>
            </div>
        </div>

        <Modal
            ref="modal"
            id="modal-add-labels"
            title="Ajouter une catégorie"
            @close="closeModal"
        >
            <template #body>
                <InputRepeater
                    id="modal-form-labels"
                    class="mb-5"
                    :fields="[
                        { key: 'name', label: 'Nom', input: Input, type: 'text' },
                        { key: 'color', label: 'Couleur', input: ColorPicker },
                        { key: 'icon', label: 'Icône', input: IconPicker }
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
import { ref, reactive, computed, onMounted, nextTick } from 'vue';
import Button from '../../../components/button/Button.vue';
import ColorPicker from '../../../components/input/ColorPicker.vue';
import ContainerApp from '../../../components/layout/container/ContainerApp.vue';
import DropdownCard from '../../../components/card/DropdownCard.vue';
import Icon from '../../../components/icon/Icon.vue';
import IconPicker from '../../../components/input/IconPicker.vue';
import Input from '../../../components/input/Input.vue';
import InputRepeater from '../../../components/input/InputRepeater.vue';
import Loader from '../../../components/loader/Loader.vue';
import Modal from '../../../components/modal/Modal.vue';
import ModalDelete from '../../../components/modal/ModalDelete.vue';
import ModalUpdate from '../../../components/modal/ModalUpdate.vue';

import useApi from '../../../composables/useApi';
import useBreakpoint from '../../../composables/useBreakpoint';
import useColor from '../../../composables/useColor';
import useDateFormat from '../../../composables/useDateFormat';
import { useToast } from '../../../plugins/useToast';

const { get, post, put, del } = useApi();
const { currentBreakpoint } = useBreakpoint();
const { getContrastColor } = useColor();
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

const columnCount = computed(() => (currentBreakpoint.value === 'sm' ? 1 : 2));

const columns = computed(() => {
    const cols = Array.from({ length: columnCount.value }, () => []);
    data.value.forEach((item, index) => {
        cols[index % columnCount.value].push(item);
    });
    return cols;
});

const modal = ref(null);
const loading = ref(false);
const data = ref([]);

const createDefaultData = () => ({
    name: '',
    color: '',
    icon: ''
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
                    icon: item.icon,
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
        } else if (!emptyField.icon?.trim()) {
            toast.error('❌ L\'icône ne peut pas être vide');
        }
        return;
    }

    try {
        loading.value = true;
        const response = await post(props.api.getLabels, formData);

        if (response.success) {
            closeModal();
            loading.value = false;
            await getLabels();
        }
    } catch (errorCatch) {
        console.error(errorCatch);
    }
};

const handleUpdate = async (data) => {
    const emptyField = !data.name?.trim() || !data.color?.trim() || !data.icon?.trim();
    if (emptyField) {
        if (!emptyField.name?.trim()) {
            toast.error('❌ Le nom ne peut pas être vide');
        } else if (!emptyField.color?.trim()) {
            toast.error('❌ La couleur ne peut pas être vide');
        } else if (!emptyField.icon?.trim()) {
            toast.error('❌ L\'icône ne peut pas être vide');
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

const closeModal = () => {
    formData.collection = [createDefaultData()];
    modal.value.close();
};

onMounted(async () => {
    await getLabels();
});
</script>