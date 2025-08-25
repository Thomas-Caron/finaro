<template>
    <ContainerApp :breadcrumbs="breadcrumbs">
        <div class="flex items-center gap-4 mb-4">
            <label class="text-sm font-semibold text-stone-900 dark:text-stone-50">Choix du template :</label>
            <select v-model="selectedTemplate" class="border rounded p-1 text-stone-900 dark:text-stone-50">
                <option value="template-1">Template 1</option>
                <option value="template-2">Template 2</option>
                <option value="template-3">Template 3</option>
            </select>
        </div>

        <div class="flex h-[600px] gap-4">
            <!-- Signature Preview au centre -->
            <div class="p-4 flex-1 flex items-center justify-center bg-stone-50 dark:bg-stone-900 rounded-lg shadow-sm">
                <SignatureEdit />
            </div>

            <!-- Toolbar à droite -->
            <div class="w-1/4 bg-stone-50 dark:bg-stone-900 rounded-lg shadow-sm flex flex-col h-full relative">
                
                <h2 v-if="selectedBlockId" class="p-4 text-lg font-bold text-stone-900 dark:text-stone-50 border-b border-stone-200 dark:border-stone-700">Édition du bloc : {{ form.blocks.find(b => b.id === selectedBlockId).type }}</h2>
                <div v-else class="p-4 text-stone-500 dark:text-stone-400">Cliquez sur un bloc pour le modifier</div>

                <div class="flex-1 overflow-y-auto">
                    <EditToolbar v-if="selectedBlockId" />
                </div>

                <div class="sticky bottom-0">
                    <button
                        type="button"
                        class="w-full p-4 flex flex-row justify-center items-center rounded-b-lg text-stone-200 dark:text-stone-600 bg-stone-800 dark:bg-stone-200 hover:bg-stone-600 dark:hover:bg-stone-400 border-t border-stone-200 dark:border-stone-700 transition duration-300 cursor-pointer"
                        data-modal-target="modal-email-signature-preview"
                        data-modal-toggle="modal-email-signature-preview"
                    >
                        <Icon class="size-5 me-2" name="Eye" />Aperçu
                    </button>
                </div>
            </div>
        </div>

        <ModalPreview id="modal-email-signature-preview" title="Aperçu" />
    </ContainerApp>
</template>

<script setup>
import { ref, reactive, computed, provide, watchEffect } from 'vue';
import ContainerApp from '../../../components/layout/container/ContainerApp.vue';
import EditToolbar from '../../../components/view/app/emailSignature/EditToolbar.vue';
import Icon from '../../../components/icon/Icon.vue';
import ModalPreview from '../../../components/modal/emailSignature/ModalPreview.vue';
import SignatureEdit from '../../../components/view/app/emailSignature/SignatureEdit.vue';
import { emailSignatureTemplates } from '../../../templates/emailSignatureTemplates';

const props = defineProps({
  url: { type: Object, default: () => ({}) },
  api: { type: Object, default: () => ({}) }
});

const breadcrumbs = computed(() => {
  return [
    { text: 'Outils' },
    { text: 'Signature de mail', url: props.url.emailSignature },
  ]
});

const selectedBlockId = ref(null);
const selectedTemplate = ref('template-1');

const form = reactive({
    blocks: [],
    styles: {}
});

const setSelectedBlockId = (id) => {
    selectedBlockId.value = id
};

provide('form', form);
provide('selectedBlockId', selectedBlockId);
provide('setSelectedBlockId', setSelectedBlockId);
provide('selectedTemplate', selectedTemplate);

watchEffect(() => {
    const template = emailSignatureTemplates[selectedTemplate.value];
    if (template) {
        form.blocks = JSON.parse(JSON.stringify(template.blocks));
        form.styles = JSON.parse(JSON.stringify(template.styles));
    }
});
</script>