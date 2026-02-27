import { ref, onMounted } from 'vue';

const isDark = ref(false);

function applyTheme() {
    document.documentElement.classList.toggle('dark', isDark.value);
}

export function useDarkMode() {
    onMounted(() => {
        const stored = localStorage.getItem('theme');
        if (stored) {
            isDark.value = stored === 'dark';
        } else {
            isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        applyTheme();
    });

    function toggle() {
        isDark.value = !isDark.value;
        localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
        applyTheme();
    }

    return { isDark, toggle };
}
