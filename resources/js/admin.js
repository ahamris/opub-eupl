import "./bootstrap";
import "./toast";
import "./datepicker";
import "./quill";
import '@tailwindplus/elements';


// Alpine.js Store - Global state management (compatible with Livewire)
document.addEventListener("alpine:init", () => {
    // Sidebar Store
    Alpine.store("sidebar", {
        isOpen: window.innerWidth > 1024, // Default open on wide screens
        toggle() {
            this.isOpen = !this.isOpen;
        },
    });

    Alpine.store("darkMode", {
        mode: (function () {
            const stored = localStorage.getItem("theme");
            if (stored === "light" || stored === "dark") return stored;
            return "system"; // default
        })(),

        get isDark() {
            if (this.mode === "dark") return true;
            if (this.mode === "light") return false;
            return window.matchMedia("(prefers-color-scheme: dark)").matches;
        },

        set(next) {
            this.mode = next;
            if (next === "light" || next === "dark") {
                localStorage.setItem("theme", next);
            } else {
                localStorage.removeItem("theme");
            }
            this.apply();
        },

        toggle() {
            this.set(this.isDark ? "light" : "dark");
        },

        apply() {
            document.documentElement.classList.toggle("dark", this.isDark);
        },

        init() {
            this.apply();
        },
    });
});
