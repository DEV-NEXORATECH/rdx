import './bootstrap';

document.addEventListener('alpine:init', () => {
    Alpine.store('ui', {
        sidebarOpen: false,
        sidebarCollapsed: window.localStorage.getItem('jobfinance-sidebar-collapsed-v2') === 'true',

        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },

        toggleCollapsed() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            window.localStorage.setItem('jobfinance-sidebar-collapsed-v2', this.sidebarCollapsed ? 'true' : 'false');
        },
    });

    Alpine.store('toasts', {
        items: [],

        add({ type = 'success', message, title = '' }) {
            const id = (Math.random() + 1).toString(36).substring(7);

            this.items.push({ id, type, title, message });

            setTimeout(() => this.remove(id), 5000);
        },

        remove(id) {
            this.items = this.items.filter((item) => item.id !== id);
        },

        intentClass(type) {
            return {
                success: 'border-success bg-success-soft text-success',
                info: 'border-primary-200 bg-primary-soft text-primary-hover',
                warning: 'border-warning bg-warning-soft text-warning',
                error: 'border-danger bg-danger-soft text-danger',
            }[type] ?? 'border-primary-200 bg-primary-soft text-primary-hover';
        },
    });

    Alpine.data('toastBoard', () => ({
        push(detail) {
            Alpine.store('toasts').add(detail);
        },
    }));
});

window.addEventListener('notify', (event) => {
    if (window.Alpine?.store('toasts')) {
        window.Alpine.store('toasts').add(event.detail ?? {});
    }
});

window.addEventListener('livewire:navigated', () => {
    const flash = document.querySelector('[data-flash-toast]');

    if (!flash || !window.Alpine?.store('toasts')) {
        return;
    }

    const { type, message } = JSON.parse(flash.dataset.flashToast);

    window.Alpine.store('toasts').add({ type, message });
    flash.remove();
});
