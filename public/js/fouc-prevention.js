// FOUC Prevention Script
// This script must run before page load to prevent flash of unstyled content

(function () {
    try {
        const stored = localStorage.getItem("theme");
        const prefersDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
        const isDark = stored === "dark" || (!stored && prefersDark);
        document.documentElement.classList.toggle("dark", isDark);
    } catch (e) {
        // Fallback: check system preference
        if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
            document.documentElement.classList.add("dark");
        }
    }
})();
