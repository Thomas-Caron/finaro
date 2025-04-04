<template>
    <component :is="icon" />
</template>
  
<script setup>
import { computed } from 'vue';
import * as icons from 'lucide-vue-next';

const props = defineProps({
    name: {
        type: String,
        required: true
    }
});

const icon = computed(() => {
    return icons[props.name];
});
  </script>