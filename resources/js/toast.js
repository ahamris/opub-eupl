// Toast Manager - Handles dynamic toast notifications
window.toastContainer = null;
window.toastManager = {
    positions: {
        "top-right": "top-4 right-4",
        "top-left": "top-4 left-4",
        "bottom-right": "bottom-4 right-4",
        "bottom-left": "bottom-4 left-4",
        "top-center": "top-4 left-1/2 -translate-x-1/2",
        "bottom-center": "bottom-4 left-1/2 -translate-x-1/2",
    },

    getContainer(position = "top-right") {
        const positionClass =
            this.positions[position] || this.positions["top-right"];
        const containerId = `toast-container-${position.replace("-", "")}`;

        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement("div");
            container.id = containerId;
            container.className = `fixed z-[100] flex flex-col gap-3 ${positionClass} pointer-events-none`;
            container.setAttribute("x-data", "{ toasts: [] }");

            document.body.appendChild(container);
        }

        return container;
    },

    show(variant = "info", message, options = {}) {
        const {
            title = null,
            icon = null,
            duration = 5000,
            dismissible = true,
            position = "top-right",
        } = options;

        const container = this.getContainer(position);
        const toastId = `toast-${Date.now()}-${Math.random()
            .toString(36)
            .substr(2, 9)}`;

        // Get classes and icon from Toast component structure
        const baseClasses =
            "flex items-start gap-3 p-4 rounded-lg border shadow-lg transition-all duration-300 max-w-sm w-full pointer-events-auto";
        const variantClasses = this.getToastClasses(variant);
        const iconData = this.getIconData(variant, icon);

        // Create toast HTML using Toast component structure
        const toastHtml = `
            <div
                id="${toastId}"
                x-data="{ show: true }"
                x-show="show"
                x-cloak
                x-transition:enter="ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-x-full"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-full"
                ${
                    duration > 0
                        ? `x-init="setTimeout(() => { show = false; setTimeout(() => $el.remove(), 200); }, ${duration})"`
                        : ""
                }
                class="${baseClasses} ${variantClasses}"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
            >
                ${iconData.html}
                <div class="flex-1 min-w-0">
                    ${
                        title
                            ? `<div class="text-sm font-semibold mb-1">${this.escapeHtml(
                                  title
                              )}</div>`
                            : ""
                    }
                    <div class="text-sm leading-5">${this.escapeHtml(
                        message
                    )}</div>
                </div>
                ${
                    dismissible
                        ? `<button type="button" @click="show = false; setTimeout(() => $el.closest('[id]').remove(), 200)" class="shrink-0 text-current/60 hover:text-current transition-colors ml-2" aria-label="Close toast"><i class="fa-solid fa-times text-sm"></i></button>`
                        : ""
                }
            </div>
        `;

        // Insert toast
        container.insertAdjacentHTML("beforeend", toastHtml);

        // Initialize Alpine on the new element
        if (window.Alpine) {
            window.Alpine.initTree(container.lastElementChild);
        }

        return toastId;
    },

    getToastClasses(variant) {
        // Matches Toast component's variant classes
        const variants = {
            success:
                "bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-700 dark:text-green-300",
            error: "bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-300",
            warning:
                "bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800 text-yellow-700 dark:text-yellow-300",
            info: "bg-sky-50 dark:bg-sky-900/20 border-sky-200 dark:border-sky-800 text-sky-700 dark:text-sky-300",
        };

        return variants[variant] || variants.info;
    },

    getIconData(variant, customIcon) {
        // Matches Toast component's icon logic
        const icons = {
            success: "check-circle",
            error: "exclamation-circle",
            warning: "triangle-exclamation",
            info: "circle-info",
        };

        const iconClasses = {
            success: "text-green-700 dark:text-green-300",
            error: "text-red-700 dark:text-red-300",
            warning: "text-yellow-700 dark:text-yellow-300",
            info: "text-sky-700 dark:text-sky-300",
        };

        const iconName = customIcon || icons[variant] || icons.info;
        const iconClass = iconClasses[variant] || iconClasses.info;

        return {
            name: iconName,
            class: iconClass,
            html: `<div class="shrink-0 mt-0.5"><i class="fa-solid fa-${iconName} ${iconClass} text-lg"></i></div>`,
        };
    },

    escapeHtml(text) {
        const div = document.createElement("div");
        div.textContent = text;
        return div.innerHTML;
    },

    success(message, options = {}) {
        return this.show("success", message, options);
    },

    error(message, options = {}) {
        return this.show("error", message, options);
    },

    warning(message, options = {}) {
        return this.show("warning", message, options);
    },

    info(message, options = {}) {
        return this.show("info", message, options);
    },
};

// Global shorthand functions
window.showToast = function (variant, message, options = {}) {
    return window.toastManager.show(variant, message, options);
};

window.toastSuccess = function (message, options = {}) {
    return window.toastManager.success(message, options);
};

window.toastError = function (message, options = {}) {
    return window.toastManager.error(message, options);
};

window.toastWarning = function (message, options = {}) {
    return window.toastManager.warning(message, options);
};

window.toastInfo = function (message, options = {}) {
    return window.toastManager.info(message, options);
};

// Auto-show session flash messages on page load
document.addEventListener("DOMContentLoaded", () => {
    if (window.flashMessages && window.toastManager) {
        Object.entries(window.flashMessages).forEach(([type, message]) => {
            if (message) {
                const variant = type === "message" ? "success" : type;
                window.toastManager.show(variant, message);
            }
        });
    }
});
