<template>
    <ContainerApp 
        class="h-[500px] flex items-center"
        :breadcrumbs="[
            { text: 'Dashboard', url: props.url.dashboard }
        ]"
    >
            <div class="text-center p-8 w-full bg-dark/30 border-2 border-dark/30 shadow-xl rounded-2xl">
                <h1 class="text-4xl font-bold text-white mb-4"><span class="me-4">🚧</span>Dashboard en construction<span class="ms-4">🚧</span></h1>
                <p class="text-lg text-white/70 mb-6">Nous travaillons actuellement sur quelque chose d'incroyable. Revenez bientôt !</p>
            </div>
    </ContainerApp>
</template>

<script setup>
import ContainerApp from '../../../components/layout/container/ContainerApp.vue';

const props = defineProps({
    url: {
        type: Object,
        default: () => ({})
    }
});
</script>