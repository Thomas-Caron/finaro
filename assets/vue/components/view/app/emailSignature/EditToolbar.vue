<template>
    <div class="divide-y divide-stone-200 dark:divide-stone-700 overflow-y-auto">
        <template v-if="currentBlock.type === 'container'">
            <div class="p-4">
                <InputBox
                    :id="`block-padding-${selectedBlockId}`"
                    :name="`block_padding_${selectedBlockId}`"
                    label="Espacement"
                    v-model="padding"
                />
            </div>
        </template>

        <template v-else-if="currentBlock.type === 'column'">
            <div class="p-4">
                <p class="text-stone-900 dark:text-stone-50 block mb-2 text-sm font-medium">Position</p>
                <div class="inline-flex rounded-md shadow-xs" role="group">
                    <button 
                        type="button"
                        :class="[
                            {
                                'text-stone-900 dark:text-stone-300 bg-stone-200/30 dark:bg-stone-700/40': currentBlock?.position === 'left',
                                'text-stone-700 dark:text-stone-400 bg-white dark:bg-stone-950': currentBlock?.position !== 'left'
                            },
                            'px-3 py-2 text-sm font-medium rounded-s-lg border border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer'
                        ]"
                        @click="toggleColumnPosition('left')"
                    >
                        <Icon class="size-5" name="PanelLeftClose" />
                    </button>
                    <button
                        type="button"
                        :class="[
                            {
                                'text-stone-900 dark:text-stone-300 bg-stone-200/30 dark:bg-stone-700/40': currentBlock?.position === 'right',
                                'text-stone-700 dark:text-stone-400 bg-white dark:bg-stone-950': currentBlock?.position !== 'right'
                            },
                            'px-3 py-2 text-sm font-medium rounded-e-lg border border-l-0 border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer'
                        ]"
                        @click="toggleColumnPosition('right')"
                    >
                        <Icon class="size-5" name="PanelRightClose" />
                    </button>
                </div>
            </div>

            <div class="p-4">
                <Slider
                    :id="`block-size-${selectedBlockId}`"
                    :name="`block_size_${selectedBlockId}`"
                    label="Largeur"
                    :min="10"
                    :max="300"
                    :step="1"
                    unit="px"
                    v-model="columnWidth"
                />
            </div>

            <div class="p-4">
                <InputBox
                    :id="`block-padding-${selectedBlockId}`"
                    :name="`block_padding_${selectedBlockId}`"
                    label="Espacement"
                    v-model="padding"
                />
            </div>

            <div class="p-4">
                <InputBox
                    :id="`block-border-${selectedBlockId}`"
                    class="mb-3"
                    :name="`block_border_${selectedBlockId}`"
                    label="Épaisseur de la bordure"
                    v-model="border"
                />
                <ColorPicker
                    :id="`block-border-color-${selectedBlockId}`"
                    :name="`block_border_color_${selectedBlockId}`"
                    label="Couleur de la bordure"
                    :custom-color="true"
                    v-model="borderColor"
                />
            </div>
        </template>
        
        <div v-else class="p-4">
            <Input
                :id="`block-content-${selectedBlockId}`"
                :name="`block_content_${selectedBlockId}`"
                label="Contenu"
                type="text"
                v-model="currentBlock.value"
            />

            <Input
                v-if="currentBlock.type === 'url'"
                :id="`block-label-${selectedBlockId}`"
                :name="`block_label_${selectedBlockId}`"
                class="mt-3"
                label="Label"
                type="text"
                v-model="currentBlock.label"
            />
        </div>

        <!-- Text & URL types -->
        <template v-if="currentBlock.type === 'text' || currentBlock.type === 'url'">
            <div class="p-4">
                <Slider
                    :id="`block-size-${selectedBlockId}`"
                    :name="`block_size_${selectedBlockId}`"
                    label="Taille"
                    :min="8"
                    :max="32"
                    :step="1"
                    unit="px"
                    v-model="fontSize"
                />
            </div>

            <div class="p-4">
                <p class="text-stone-900 dark:text-stone-50 block mb-2 text-sm font-medium">Styles</p>
                <div class="inline-flex rounded-md shadow-xs" role="group">
                    <button 
                        type="button"
                        :class="[
                            {
                                'text-stone-900 dark:text-stone-300 bg-stone-200/30 dark:bg-stone-700/40': form.styles[selectedBlockId]?.fontWeight === 'bold',
                                'text-stone-700 dark:text-stone-400 bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.fontWeight !== 'bold'
                            },
                            'px-3 py-2 text-sm font-medium rounded-s-lg border border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer'
                        ]"
                        @click="toggleStyle('fontWeight', 'bold')"
                    >
                        <Icon class="size-5" name="Bold" />
                    </button>
                    <button
                        type="button"
                        :class="[
                            {
                                'text-stone-900 dark:text-stone-300 bg-stone-200/30 dark:bg-stone-700/40': form.styles[selectedBlockId]?.fontStyle === 'italic',
                                'text-stone-700 dark:text-stone-400 bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.fontStyle !== 'italic'
                            },
                            'px-3 py-2 text-sm font-medium border-t border-b border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer'
                        ]"
                        @click="toggleStyle('fontStyle', 'italic')"
                    >
                        <Icon class="size-5" name="Italic" />
                    </button>
                    <button
                        type="button"
                        :class="[
                            {
                                'text-stone-900 dark:text-stone-300 bg-stone-200/30 dark:bg-stone-700/40': form.styles[selectedBlockId]?.textDecoration === 'underline',
                                'text-stone-700 dark:text-stone-400 bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.textDecoration !== 'underline'
                            },
                            'px-3 py-2 text-sm font-medium rounded-e-lg border border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer'
                        ]"
                        @click="toggleStyle('textDecoration', 'underline')"
                    >
                        <Icon class="size-5" name="Underline" />
                    </button>
                </div>
            </div>

            <div class="p-4">
                <p class="text-stone-900 dark:text-stone-50 block mb-2 text-sm font-medium">Majuscule / minuscule</p>
                <div class="inline-flex rounded-md shadow-xs" role="group">
                    <button 
                        type="button"
                        :class="[
                            {
                                'text-stone-900 dark:text-stone-300 bg-stone-200/30 dark:bg-stone-700/40': form.styles[selectedBlockId]?.textTransform === 'uppercase',
                                'text-stone-700 dark:text-stone-400 bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.textTransform !== 'uppercase'
                            },
                            'px-3 py-2 text-sm font-medium rounded-s-lg border border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer'
                        ]"
                        @click="toggleStyle('textTransform', 'uppercase')"
                    >
                        <Icon class="size-5" name="CaseUpper" />
                    </button>
                    <button
                        type="button"
                        :class="[
                            {
                                'text-stone-900 dark:text-stone-300 bg-stone-200/30 dark:bg-stone-700/40': form.styles[selectedBlockId]?.textTransform === 'lowercase',
                                'text-stone-700 dark:text-stone-400 bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.textTransform !== 'lowercase'
                            },
                            'px-3 py-2 text-sm font-medium rounded-e-lg border border-s-0 border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer'
                        ]"
                        @click="toggleStyle('textTransform', 'lowercase')"
                    >
                        <Icon class="size-5" name="CaseLower" />
                    </button>
                </div>
            </div>

            <div class="p-4">
                <p class="text-stone-900 dark:text-stone-50 block mb-2 text-sm font-medium">Espacement des lettres</p>
                <div class="inline-flex rounded-md shadow-xs" role="group">
                    <button
                        type="button"
                        :class="[
                            {
                                'bg-stone-200/30 dark:bg-stone-700/40': form.styles[selectedBlockId]?.letterSpacing === '-1px',
                                'bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.letterSpacing !== '-1px'
                            },
                            'px-3 py-2 rounded-s-lg border border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 cursor-pointer group'
                        ]"
                        @click="toggleStyle('letterSpacing', '-1px')"
                    >
                        <IconSvg 
                            :class="[
                                {
                                    'text-stone-900 dark:text-stone-300': form.styles[selectedBlockId]?.letterSpacing === '-1px',
                                    'text-stone-700 dark:text-stone-400': form.styles[selectedBlockId]?.letterSpacing !== '-1px'
                                },
                                'size-6 font-medium group-hover:text-stone-900 dark:group-hover:text-stone-300'
                            ]"
                            name="kerning-tight"
                        />
                    </button>
                    <button
                        type="button"
                        :class="[
                            {
                                'bg-stone-200/30 dark:bg-stone-700/40': !form.styles[selectedBlockId]?.letterSpacing || form.styles[selectedBlockId]?.letterSpacing === '0px',
                                'bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.letterSpacing && form.styles[selectedBlockId]?.letterSpacing !== '0px'
                            },
                            'px-3 py-2  border border-l-0 border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 cursor-pointer group'
                        ]"
                        @click="toggleStyle('letterSpacing', '0px')"
                    >
                        <IconSvg 
                            :class="[
                                {
                                    'text-stone-900 dark:text-stone-300': !form.styles[selectedBlockId]?.letterSpacing || form.styles[selectedBlockId]?.letterSpacing === '0px',
                                    'text-stone-700 dark:text-stone-400': form.styles[selectedBlockId]?.letterSpacing && form.styles[selectedBlockId]?.letterSpacing !== '0px'
                                },
                                'size-6 font-medium group-hover:text-stone-900 dark:group-hover:text-stone-300'
                            ]"
                            name="kerning-normal"
                        />
                    </button>
                    <button
                        type="button"
                        :class="[
                            {
                                'bg-stone-200/30 dark:bg-stone-700/40': form.styles[selectedBlockId]?.letterSpacing === '2px',
                                'bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.letterSpacing !== '2px'
                            },
                            'px-3 py-2 border-t border-b border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 cursor-pointer group'
                        ]"
                        @click="toggleStyle('letterSpacing', '2px')"
                    >
                        <IconSvg 
                            :class="[
                                {
                                    'text-stone-900 dark:text-stone-300': form.styles[selectedBlockId]?.letterSpacing === '2px',
                                    'text-stone-700 dark:text-stone-400': form.styles[selectedBlockId]?.letterSpacing !== '2px'
                                },
                                'size-6 font-medium group-hover:text-stone-900 dark:group-hover:text-stone-300'
                            ]"
                            name="kerning-generous"
                        />
                    </button>
                    <button
                        type="button"
                        :class="[
                            {
                                'bg-stone-200/30 dark:bg-stone-700/40': form.styles[selectedBlockId]?.letterSpacing === '5px',
                                'bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.letterSpacing !== '5px'
                            },
                            'px-3 py-2 rounded-e-lg border border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 cursor-pointer group'
                        ]"
                        @click="toggleStyle('letterSpacing', '5px')"
                    >
                        <IconSvg 
                            :class="[
                                {
                                    'text-stone-900 dark:text-stone-300': form.styles[selectedBlockId]?.letterSpacing === '5px',
                                    'text-stone-700 dark:text-stone-400': form.styles[selectedBlockId]?.letterSpacing !== '5px'
                                },
                                'size-6 font-medium group-hover:text-stone-900 dark:group-hover:text-stone-300'
                            ]"
                            name="kerning-really-generous"
                        />
                    </button>
                </div>
            </div>

            <div class="p-4">
                <p class="text-stone-900 dark:text-stone-50 block mb-2 text-sm font-medium">Alignement</p>
                <div class="inline-flex rounded-md shadow-xs" role="group">
                    <button 
                        type="button"
                        :class="[
                            {
                                'text-stone-900 dark:text-stone-300 bg-stone-200/30 dark:bg-stone-700/40': !form.styles[selectedBlockId]?.textAlign || form.styles[selectedBlockId]?.textAlign === 'left',
                                'text-stone-700 dark:text-stone-400 bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.textAlign && form.styles[selectedBlockId]?.textAlign !== 'left'
                            },
                            'px-3 py-2 text-sm font-medium rounded-s-lg border border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer'
                        ]"
                        @click="toggleStyle('textAlign', 'left')"
                    >
                        <Icon class="size-5" name="AlignLeft" />
                    </button>
                    <button
                        type="button"
                        :class="[
                            {
                                'text-stone-900 dark:text-stone-300 bg-stone-200/30 dark:bg-stone-700/40': form.styles[selectedBlockId]?.textAlign === 'center',
                                'text-stone-700 dark:text-stone-400 bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.textAlign !== 'center'
                            },
                            'px-3 py-2 text-sm font-medium border-t border-b border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer'
                        ]"
                        @click="toggleStyle('textAlign', 'center')"
                    >
                        <Icon class="size-5" name="AlignCenter" />
                    </button>
                    <button
                        type="button"
                        :class="[
                            {
                                'text-stone-900 dark:text-stone-300 bg-stone-200/30 dark:bg-stone-700/40': form.styles[selectedBlockId]?.textAlign === 'right',
                                'text-stone-700 dark:text-stone-400 bg-white dark:bg-stone-950': form.styles[selectedBlockId]?.textAlign !== 'right'
                            },
                            'px-3 py-2 text-sm font-medium rounded-e-lg border border-stone-500 hover:bg-stone-200/30 dark:hover:bg-stone-700/40 hover:text-stone-900 dark:hover:text-stone-300 cursor-pointer'
                        ]"
                        @click="toggleStyle('textAlign', 'right')"
                    >
                        <Icon class="size-5" name="AlignRight" />
                    </button>
                </div>
            </div>

            <div class="p-4">
                <ColorPicker
                    :id="`block-color-${selectedBlockId}`"
                    :name="`block_color_${selectedBlockId}`"
                    label="Couleur du texte"
                    :custom-color="true"
                    v-model="form.styles[selectedBlockId].color"
                />
            </div>

            <div class="p-4">
                <InputBox
                    :id="`block-padding-${selectedBlockId}`"
                    :name="`block_padding_${selectedBlockId}`"
                    label="Espacement"
                    v-model="padding"
                />
            </div>

            <div class="p-4">
                <InputBox
                    :id="`block-border-${selectedBlockId}`"
                    class="mb-3"
                    :name="`block_border_${selectedBlockId}`"
                    label="Épaisseur de la bordure"
                    v-model="border"
                />
                <ColorPicker
                    :id="`block-border-color-${selectedBlockId}`"
                    :name="`block_border_color_${selectedBlockId}`"
                    label="Couleur de la bordure"
                    :custom-color="true"
                    v-model="borderColor"
                />
            </div>
        </template>

        <!-- Image type -->
        <template v-if="currentBlock.type === 'image'">
            <div class="p-4">
                <Slider
                    :id="`block-size-${selectedBlockId}`"
                    :name="`block_size_${selectedBlockId}`"
                    class="mb-3"
                    label="Largeur"
                    :min="10"
                    :max="300"
                    :step="1"
                    unit="px"
                    v-model="imageWidth"
                />
            </div>

            <div class="p-4">
                <Slider
                    :id="`block-border-radius-${selectedBlockId}`"
                    :name="`block_border-radius_${selectedBlockId}`"
                    class="mb-3"
                    label="Rayon de bordure"
                    :min="0"
                    :max="100"
                    :step="1"
                    unit="percentage"
                    v-model="borderRadius"
                />
                <Slider
                    :id="`block-border-width-${selectedBlockId}`"
                    :name="`block_border-width_${selectedBlockId}`"
                    class="mb-3"
                    label="Épaisseur de la bordure"
                    :min="0"
                    :max="10"
                    :step="1"
                    unit="px"
                    v-model="borderWidth"
                />
                <ColorPicker
                    :id="`block-border-color-${selectedBlockId}`"
                    :name="`block_border_color_${selectedBlockId}`"
                    label="Couleur de la bordure"
                    :custom-color="true"
                    v-model="form.styles[selectedBlockId].borderColor"
                />
            </div>
        </template>
    </div>
</template>

<script setup>
import { inject, computed, nextTick, onMounted, watch } from 'vue';
import ColorPicker from '../../../input/ColorPicker.vue';
import Icon from '../../../icon/Icon.vue';
import IconSvg from '../../../icon/IconSvg.vue';
import Input from '../../../input/Input.vue';
import InputBox from '../../../input/InputBox.vue';
import Slider from '../../../input/Slider.vue';
import { initDropdowns } from 'flowbite';

import useConvertFilter from '../../../../composables/useConvertFilter';

const { capitalize } = useConvertFilter();

const form = inject('form');
const selectedBlockId = inject('selectedBlockId');

const currentBlock = computed(() => {
    return form.blocks.find(b => b.id === selectedBlockId.value);
});

const createStyleComputed = (prop, value, unit) => {
    return computed({
        get() {
            const raw = form.styles[selectedBlockId.value]?.[prop] || value;
            return parseInt(raw);
        },
        set(value) {
            if (!form.styles[selectedBlockId.value]) {
                form.styles[selectedBlockId.value] = {};
            }
            form.styles[selectedBlockId.value][prop] = `${value}${unit}`;

            if (prop === 'borderWidth' && value > 0) {
                form.styles[selectedBlockId.value].borderStyle = 'solid';
            }
        }
    });
};

const border = computed({
    get() {
        const style = form.styles[selectedBlockId.value];

        return {
            top: (style.borderTop || '0').replace('px', ''),
            right: (style.borderRight || '0').replace('px', ''),
            bottom: (style.borderBottom || '0').replace('px', ''),
            left: (style.borderLeft || '0').replace('px', '')
        };
    },
    set(val) {
        const style = form.styles[selectedBlockId.value];
        const color = style.borderTopColor ||
            style.borderRightColor ||
            style.borderBottomColor ||
            style.borderLeftColor ||
            style.borderColor;
        const setSide = (side) => {
            style[`border${capitalize(side)}`] = `${val[side] || 0}px`;
            style[`border${capitalize(side)}Style`] = val[side] > 0 ? 'solid' : 'none';
            if (color) {
                style[`border${capitalize(side)}Color`] = color;
            }
        };

        ['top', 'right', 'bottom', 'left'].forEach(setSide);
    }
});

const padding = computed({
    get() {
        const style = form.styles[selectedBlockId.value];

        return {
            top: (style.paddingTop || '0').replace('px', ''),
            right: (style.paddingRight || '0').replace('px', ''),
            bottom: (style.paddingBottom || '0').replace('px', ''),
            left: (style.paddingLeft || '0').replace('px', '')
        };
    },
    set(val) {
        const style = form.styles[selectedBlockId.value];
        const setSide = (side) => {
            style[`padding${capitalize(side)}`] = `${val[side] || 0}px`;
        };

        ['top', 'right', 'bottom', 'left'].forEach(setSide);
    }
});

const borderColor = computed({
    get() {
        const style = form.styles[selectedBlockId.value];
        return (
            style.borderTopColor ||
            style.borderRightColor ||
            style.borderBottomColor ||
            style.borderLeftColor ||
            style.borderColor
        );
    },
    set(val) {
        const style = form.styles[selectedBlockId.value];
        const setSide = (side) => {
            if (!style[`border${capitalize(side)}`]) {
                style[`border${capitalize(side)}`] = '0px';
                style[`border${capitalize(side)}Style`] = 'solid';
            }
            style[`border${capitalize(side)}Color`] = val;
        };

        ['top', 'right', 'bottom', 'left'].forEach(setSide);
    }
});

const toggleStyle = (prop, value) => {
    const currentValue = form.styles[selectedBlockId.value]?.[prop];

    if (currentValue === value) {
        form.styles[selectedBlockId.value][prop] = null;
    } else {
        form.styles[selectedBlockId.value][prop] = value;
    }
};

const toggleColumnPosition = (newPosition) => {
    const otherColumn = form.blocks.find((b) => b.type === 'column' && b.id !== currentBlock.value.id);

    // Si l'autre colonne a déjà cette position, on l'inverse
    if (otherColumn && otherColumn.position === newPosition) {
        otherColumn.position = currentBlock.value.position;
    }

    // Mettre à jour la position du bloc courant
    currentBlock.value.position = newPosition;
};

const columnWidth = createStyleComputed('width', '10px', 'px');
const fontSize = createStyleComputed('fontSize', '14px', 'px');
const imageWidth = createStyleComputed('width', '10px', 'px');
const borderRadius = createStyleComputed('borderRadius', '0%', '%');
const borderWidth = createStyleComputed('borderWidth', '0px', 'px');

watch(selectedBlockId, async () => {
    await nextTick();
    initDropdowns();
});

onMounted(async () => {
    await nextTick();
    initDropdowns();
});
</script>