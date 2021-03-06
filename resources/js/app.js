window._ = require('lodash');
window.URI = require('urijs');
window.axios = require('axios');
window._ = require('underscore');
window.moment = require('moment/moment');
window.Promise = require('promise');
window.Cookies = require('js-cookie');

/*
 * Define Moment locales
 */
window.moment.defineLocale('en-short', {
    parentLocale: 'en',
    relativeTime : {
        future: "in %s",
        past:   "%s",
        s:  "1s",
        m:  "1m",
        mm: "%dm",
        h:  "1h",
        hh: "%dh",
        d:  "1d",
        dd: "%dd",
        M:  "1 month ago",
        MM: "%d months ago",
        y:  "1y",
        yy: "%dy"
    }
});
window.moment.locale('en');

// JQuery
if (window.$ === undefined || window.jQuery === undefined) {
    window.$ = window.jQuery = require('jquery');
}

// Date picker
require('jquery-ui/ui/widgets/datepicker');

// Popper.js
require('popper.js/dist/popper')

// Bootstrap
require('bootstrap/dist/js/bootstrap');
require('bootstrap-table/dist/bootstrap-table');

// Sweetalert
window.Swal = require('sweetalert2/dist/sweetalert2');

/**
 * Load Vue - if this application is using Vue as its framework.
 */
if ($('#app').length > 0) {
    require('./vue/bootstrap');
}