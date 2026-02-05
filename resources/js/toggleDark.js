const STORAGE_KEY = 'theme'; // 'light' | 'dark'

function systemPrefersDark() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
}

function updateThemeIcon(theme) {
    const moon = document.getElementById('theme-icon-moon');
    const sun = document.getElementById('theme-icon-sun');
    const btn = document.getElementById('theme-toggle');

    if (!moon || !sun || !btn) return;

    const isDark = theme === 'dark';

    // Dark mode ON => show sun
    sun.classList.toggle('hidden', !isDark);
    moon.classList.toggle('hidden', isDark);

    btn.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
    btn.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');
}

function applyTheme(theme) {
    const isDark = theme === 'dark';
    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.dataset.theme = theme;
    updateThemeIcon(theme);
}

function getInitialTheme() {
    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved === 'light' || saved === 'dark') return saved;
    return systemPrefersDark() ? 'dark' : 'light';
}

function toggleTheme() {
    const isCurrentlyDark = document.documentElement.classList.contains('dark');
    const next = isCurrentlyDark ? 'light' : 'dark';
    localStorage.setItem(STORAGE_KEY, next);
    applyTheme(next);
}

document.addEventListener('DOMContentLoaded', () => {
    applyTheme(getInitialTheme());

    const btn = document.getElementById('theme-toggle');
    if (btn) btn.addEventListener('click', toggleTheme);
});
