/*
 * Load Vue & Vue-Resource.
 */
if (window.Vue === undefined) {
    window.Vue = require('vue');
    window.Bus = new Vue();
}

/**
 * Load Vue Global Mixin.
 */
Vue.mixin(require('./mixin'));

/**
 * Define the Vue filters.
 */
require('./filters');

/**
 * Load the Vue components
 */
require('./components/bootstrap');


