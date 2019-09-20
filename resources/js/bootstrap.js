try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}


let token = document.head.querySelector('meta[name="csrf-token"]');

try {
    window.axios = require('axios');
    window.axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN' : token
    };
} catch (e) {

}
