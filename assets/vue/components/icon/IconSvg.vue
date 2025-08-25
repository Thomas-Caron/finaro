<template>
    <component :is="icons[name]" />
</template>

<script setup>
import KerningGenerous from './kerning/KerningGenerous.vue';
import KerningNormal from './kerning/KerningNormal.vue';
import KerningReallyGenerous from './kerning/KerningReallyGenerous.vue';
import KerningTight from './kerning/KerningTight.vue';

const props = defineProps({ name: String });

const icons = {
    'kerning-tight': KerningTight,
    'kerning-normal': KerningNormal,
    'kerning-generous': KerningGenerous,
    'kerning-really-generous': KerningReallyGenerous
};
</script>