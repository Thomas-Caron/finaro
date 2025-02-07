import { startStimulusApp } from '@symfony/stimulus-bundle';

import SliderController from './controllers/slider_controller.js';
import ThemeController from './controllers/theme_controller.js';

const app = startStimulusApp();

app.register('slider_controller', SliderController);
app.register('theme_controller', ThemeController);
