import { defineStore } from 'pinia';
import { ref, computed, onMounted } from 'vue';

export const useSidebarStore = defineStore('sidebar', () => {
    const isCollapsed = ref(false);
    const isMobileOpen = ref(false);
    const screenWidth = ref(window.innerWidth);

    const isSmallScreen = computed(() => screenWidth.value < 640);

    function updateScreenWidth() {
        screenWidth.value = window.innerWidth;
        if (isSmallScreen.value) {
            isCollapsed.value = false;
            isMobileOpen.value = false;
        }
    }

    function toggle() {
        if (isSmallScreen.value) {
            isMobileOpen.value = !isMobileOpen.value;
        } else {
            isCollapsed.value = !isCollapsed.value;
        }
    };

    onMounted(() => {
        window.addEventListener('resize', updateScreenWidth);
        updateScreenWidth();
    });

    return { isCollapsed, isSmallScreen, isMobileOpen, toggle }
});