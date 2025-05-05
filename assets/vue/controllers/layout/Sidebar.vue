<template>
    <Sidebar :user="user" :url="url" :nav="nav" />
</template>

<script setup>
import Sidebar from '../../components/layout/sidebar/Sidebar.vue';

const props = defineProps({
    user: { type: Object, default: () => ({}) },
    url: { type: Object, default: () => ({}) },
    nav: { type: Object, default: () => ({}) }
})
</script>