import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    toggle() {
        const html = document.documentElement;

        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    connect() {
        const theme = localStorage.getItem('theme');
        const checkbox = document.querySelector('#switch-mode');

        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            if (checkbox) {
                checkbox.checked = true;
            }
        } else {
            document.documentElement.classList.remove('dark');
            if (checkbox) {
                checkbox.checked = false;
            }
        }
    }
}