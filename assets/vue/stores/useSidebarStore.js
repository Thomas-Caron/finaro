import { defineStore } from 'pinia';
import { ref, computed, onMounted, watch } from 'vue';

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
        } else {
            const stored = localStorage.getItem('sidebar-collapsed');
            isCollapsed.value = stored === 'true';
        }
    }

    function toggle() {
        if (isSmallScreen.value) {
            isMobileOpen.value = !isMobileOpen.value;
            if (isMobileOpen.value) {
                document.body.classList.add('no-scroll');
            } else {
                document.body.classList.remove('no-scroll');
            }
        } else {
            isCollapsed.value = !isCollapsed.value;
        }
    };

    function closeOnMobile() {
        if (isSmallScreen.value) {
            isMobileOpen.value = false;
        }
    };

    onMounted(() => {
        window.addEventListener('resize', updateScreenWidth);
        updateScreenWidth();
    });

    watch(isCollapsed, (newVal) => {
        if (!isSmallScreen.value) {
            localStorage.setItem('sidebar-collapsed', newVal.toString());
        }
    });

    return { isCollapsed, isSmallScreen, isMobileOpen, toggle, closeOnMobile }
});