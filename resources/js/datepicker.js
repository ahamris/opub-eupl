import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

// Make flatpickr globally available
window.flatpickr = flatpickr;

// Theme loader function - dynamically loads theme CSS from local assets
window.loadFlatpickrTheme = function (theme) {
    if (!theme || theme === "light") {
        // Remove all theme CSS if switching to light
        const allThemeLinks = document.querySelectorAll(
            'link[id^="flatpickr-theme-"]'
        );
        allThemeLinks.forEach(function (link) {
            link.remove();
        });
        return; // Light is default, no CSS needed
    }

    const themeId = `flatpickr-theme-${theme}`;

    // Check if this specific theme is already loaded
    if (document.getElementById(themeId)) {
        return;
    }

    // Remove all other theme CSS files first
    const allThemeLinks = document.querySelectorAll(
        'link[id^="flatpickr-theme-"]'
    );
    allThemeLinks.forEach(function (link) {
        if (link.id !== themeId) {
            link.remove();
        }
    });

    // Create link element
    const link = document.createElement("link");
    link.id = themeId;
    link.rel = "stylesheet";

    // Load from local assets
    link.href = `/assets/flatpickr/themes/${theme}.css`;

    document.head.appendChild(link);
};

// Locale loader function - dynamically loads locale files
window.loadFlatpickrLocale = function (locale) {
    if (!locale || locale === "en" || locale === "default") {
        return; // English is default
    }

    const localeId = `flatpickr-locale-${locale}`;

    // Check if locale is already loaded
    if (document.getElementById(localeId)) {
        return;
    }

    // Create script element
    const script = document.createElement("script");
    script.id = localeId;
    script.src = `/assets/flatpickr/locales/${locale}.js`;
    script.async = true;

    // Mark as loaded when done
    script.onload = function () {
        script.dataset.loaded = "true";
    };

    document.head.appendChild(script);
};
