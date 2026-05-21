import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]')?.content;

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import './echo'; // echo.js sudah ada Pusher/Echo di dalamnya, tidak perlu import ulang
