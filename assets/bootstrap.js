import { startStimulusApp } from '@symfony/stimulus-bundle';

import ChartCompoundInterestController from './controllers/chart_compound_interest_controller.js';
import ChartMortgateLoanController from './controllers/chart_mortgate_loan_controller.js';
import SliderController from './controllers/slider_controller.js';
import ThemeController from './controllers/theme_controller.js';

const app = startStimulusApp();

app.register('chart_compound_interest_controller', ChartCompoundInterestController);
app.register('chart_mortgate_loan_controller', ChartMortgateLoanController);
app.register('slider_controller', SliderController);
app.register('theme_controller', ThemeController);
