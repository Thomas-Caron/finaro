<template>
    <Navbar :is-connected="isConnected" :user="user" :url="url" />
</template>

<script setup>
import Navbar from '../../components/layout/navbar/Navbar.vue';

const props = defineProps({
    isConnected: {
        type: Boolean,
        default: false
    },
    user: {
        type: Object,
        default: () => ({})
    },
    url: {
        type: Object,
        default: () => ({})
    }
});
</script>