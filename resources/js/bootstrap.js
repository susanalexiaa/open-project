import Echo from 'laravel-echo';

window._ = require('lodash');

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import moment from "moment";
import 'moment/locale/ru';
moment.locale('ru')
window.moment = moment;
