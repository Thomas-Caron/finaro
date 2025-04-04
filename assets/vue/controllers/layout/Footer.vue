<template>
    <FooterApp v-if="isApp" />
    <Footer v-else :url="url" />
</template>

<script setup>
import Footer from '../../components/layout/footer/Footer.vue';
import FooterApp from '../../components/layout/footer/FooterApp.vue';

const props = defineProps({
    isApp: {
        type: Boolean,
        default: false
    },
    url: {
        type: Object,
        default: () => ({})
    }
});
</script>