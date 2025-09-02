import { ref, onMounted, onUnmounted } from 'vue';

const useBreakpoint = () => {
    const breakpoints = {
        sm: 0,
        md: 768,
        lg: 1024,
        xl: 1280,
        xxl: 1536,
    };

    const currentBreakpoint = ref('sm');

    const handleResize = () => {
        const width = window.innerWidth;

        if (width >= breakpoints.xxl) currentBreakpoint.value = '2xl';
        else if (width >= breakpoints.xl) currentBreakpoint.value = 'xl';
        else if (width >= breakpoints.lg) currentBreakpoint.value = 'lg';
        else if (width >= breakpoints.md) currentBreakpoint.value = 'md';
        else currentBreakpoint.value = 'sm';
    };

    onMounted(() => {
        handleResize();
        window.addEventListener('resize', handleResize);
    });

    onUnmounted(() => {
        window.removeEventListener('resize', handleResize);
    });


    return {
        currentBreakpoint
    };
}

export default useBreakpoint;