import './bootstrap.js';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import './styles/tailwind.css';
import './styles/app.scss';

import 'flowbite';

document.addEventListener('turbo:load', () => {
    window.initFlowbite();
});

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
