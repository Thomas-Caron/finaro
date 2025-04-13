<template>
    <div
        :class="[
            {
                'ml-64': !sidebar.isCollapsed,
                'ml-20': sidebar.isCollapsed
            },
            'px-6 h-[500px] flex items-center'
        ]"
    >
        <div class="text-center p-8 w-full bg-dark/30 border-2 border-dark/30 shadow-xl rounded-2xl">
            <h1 class="text-4xl font-bold text-white mb-4"><span class="me-4">🚧</span>Simulateur d'intérêts composés en construction<span class="ms-4">🚧</span></h1>
            <p class="text-lg text-white/70 mb-6">Nous travaillons actuellement sur quelque chose d'incroyable. Revenez bientôt !</p>
        </div>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';

import { useBreadcrumbStore } from '../../../../stores/useBreadcrumbStore';
import { useSidebarStore } from '../../../../stores/useSidebarStore';

const props = defineProps({
    url: {
        type: Object,
        default: () => ({})
    }
});

const breadcrumb = useBreadcrumbStore();
const sidebar = useSidebarStore();

onMounted(() => {
    breadcrumb.set([
        { text: 'Simulateurs' },
        { text: 'Intérêts composés', url: props.url.compoundInterest },
    ]);
});

onUnmounted(() => {
    breadcrumb.clear()
});
</script>