

import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

Chart.defaults.font.family = 'Public Sans, system-ui, sans-serif';
Chart.defaults.color = '#4e525b';

export { Chart };
