import { startStimulusApp } from '@symfony/stimulus-bundle';

import ThemeController from './controllers/theme_controller.js';

const app = startStimulusApp();

app.register('theme_controller', ThemeController);
