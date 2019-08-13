import Vue from 'vue';
import globalMixins from './mixin';

/*
 * Load Vue & Vue-Resource.
 */
if (window.Vue === undefined) {
    window.Vue = Vue;
    window.Bus = new Vue();
}

/**
 * Load Vue Global Mixin.
 */
Vue.mixin(globalMixins);

/**
 * Define the Vue filters.
 */
require('./filters');

/**
 * Load the Vue components
 */
require('./components/bootstrap');


