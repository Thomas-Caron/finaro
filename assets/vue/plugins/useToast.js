import { createApp, h, ref } from 'vue';
import ToastManager from '../components/toast/ToastManager.vue';

let toast = null;

export const createToastPlugin = () => {
    const manager = ref(null);

    const container = document.createElement('div');
    document.body.appendChild(container);

    const app = createApp({
        setup() {
            toast = {
                show: (message, options = {}) => {
                    manager.value?.addToast({ message, ...options });
                },
                success: (message, opts) => toast.show(message, { ...opts, type: 'success' }),
                error: (message, opts) => toast.show(message, { ...opts, type: 'error' }),
                info: (message, opts) => toast.show(message, { ...opts, type: 'info' }),
                default: (message, opts) => toast.show(message, { ...opts, type: 'default' }),
            };
            return () => h(ToastManager, { ref: manager });
        }
    });

    app.mount(container);
};

export const useToast = () => {
    if (!toast) {
        throw new Error('Toast plugin not initialized. Call createToastPlugin() in main.js');
    }
    return toast;
};