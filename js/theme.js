/**
 * ECUSTA Theme Manager — Dark / Light Mode
 * Persists theme preference via localStorage.
 */
(function () {
    'use strict';

    const STORAGE_KEY = 'ecusta-theme';

    function getPreferredTheme() {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored) return stored;
        // Default to dark
        return 'dark';
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        // Update all toggle icons on the page
        document.querySelectorAll('.theme-toggle .material-icons').forEach(icon => {
            icon.textContent = theme === 'light' ? 'dark_mode' : 'light_mode';
        });
    }

    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-theme') || 'dark';
        const next = current === 'dark' ? 'light' : 'dark';
        localStorage.setItem(STORAGE_KEY, next);
        applyTheme(next);
    }

    // Apply immediately (before DOM ready) to prevent flash
    applyTheme(getPreferredTheme());

    // Once DOM is ready, wire up toggle buttons and update icons
    document.addEventListener('DOMContentLoaded', function () {
        applyTheme(getPreferredTheme());
        document.querySelectorAll('.theme-toggle').forEach(btn => {
            btn.addEventListener('click', toggleTheme);
        });
    });

    // Expose globally for onclick attributes if needed
    window.toggleTheme = toggleTheme;
})();
