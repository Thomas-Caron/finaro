import { ref } from 'vue';

const isDark = ref(document.documentElement.classList.contains('dark'));

const updateIsDark = () => {
    isDark.value = document.documentElement.classList.contains('dark');
};

const observer = new MutationObserver(updateIsDark);

observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class'],
});

const setTheme = (theme) => {
    const html = document.documentElement;
    if (theme === 'dark') {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
    updateIsDark();
};

const toggleTheme = () => {
    const html = document.documentElement;
    const isCurrentlyDark = html.classList.contains('dark');
    setTheme(isCurrentlyDark ? 'light' : 'dark');
};

const initTheme = () => {
    const theme = localStorage.getItem('theme') || 'light';
    setTheme(theme);
};

export default function useTheme() {
    return {
        isDark,
        toggleTheme,
        setTheme,
        initTheme,
    };
}