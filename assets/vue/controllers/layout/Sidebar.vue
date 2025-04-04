<template>
    <Sidebar :user="user" />
</template>

<script setup>
import Sidebar from '../../components/layout/sidebar/Sidebar.vue';

const props = defineProps({
    user: {
        type: Object,
        default: () => ({})
    }
})
</script>