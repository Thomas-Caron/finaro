<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center p-8 bg-dark/30 border-2 border-dark/30 shadow-xl rounded-2xl">
            <h1 class="text-4xl font-bold text-white mb-4"><span class="me-4">🚧</span>Site en construction<span class="ms-4">🚧</span></h1>
            <p class="text-lg text-white/70 mb-6">Nous travaillons actuellement sur quelque chose d'incroyable. Revenez bientôt !</p>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';

const props = defineProps({
    apiToken: {
        type: String,
        default: null
    },
    url: {
        type: String,
        default: '/'
    }
});

onMounted(() => {
    if (props.apiToken && props.apiToken.trim() !== '') {
        localStorage.setItem('apiToken', props.apiToken);

        const url = new URL(window.location.href);
        url.searchParams.delete('token');
        window.history.replaceState({}, document.title, url.pathname + url.search);
    }
});
</script>