import Vue from 'vue';
import Vuex from 'vuex'
import VueRouter from 'vue-router'

import LocalStorage from 'vue-ls';
import VueSessionStorage from 'vue-sessionstorage'
import VModal from 'vue-js-modal'
import VueScrollTo from 'vue-scrollto'
import Rollbar from 'vue-rollbar';

import globalMixins from './mixin';

// Vue router routes
import routes from './routes';

// vuex modules
import globalStoreModule from './store/modules/global'
import sessionReportsStoreModule from './store/modules/session-reports'
import expensesStoreModule from './store/modules/expenses'
import documentsStoreModule from './store/modules/documents'

// vue reusable components
import app from './components/app';
import statusBox from './components/status-box/root';
import navbar from './components/layout/navbar/navbar';
import settings from './components/settings/settings';
import profile from './components//settings/profile';
import updateContactInformation from './components/settings/profile/update-contact-information';
import updateProfilePhoto from './components/settings/profile/update-profile-photo';
import security from './components/settings/security';
import updatePassword from './components/settings/security/update-password';
import register from './components/register/register';
import calendar from './components/calendar/calendar';
import sessionReportEdit from './components/session-reports/edit'

// vue pages
import documentUploadIndex from './pages/documents/upload';
import documentBrowseIndex from './pages/documents/browse';

// env
const production = process.env.NODE_ENV === 'production'

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
 * Load the Vue global components
 */
Vue.component('status-box', statusBox)
Vue.component('nav-bar', navbar)
Vue.component('settings', settings)
Vue.component('profile', profile)
Vue.component('update-contact-information', updateContactInformation)
Vue.component('update-profile-photo', updateProfilePhoto)
Vue.component('security', security)
Vue.component('update-password', updatePassword)
Vue.component('register', register)
Vue.component('calendar', calendar)
Vue.component('document-upload-index', documentUploadIndex)
Vue.component('document-browse-index', documentBrowseIndex)
Vue.component('session-report-editor', sessionReportEdit)

// Vue plugins#
Vue.use(VueRouter)
Vue.use(Vuex)

Vue.use(LocalStorage, {
    namespace: 'tkn',
    name: 'ls',
    storage: 'local'
});
Vue.use(VueSessionStorage)

Vue.use(VModal, { dynamic: true, dynamicDefaults: { clickToClose: false } })

Vue.use(VueScrollTo)

Vue.use(Rollbar, {
    accessToken: 'ff3f9c1da39e4b37bac06f8da7a7a823',
    captureUncaught: true,
    captureUnhandledRejections: true,
    enabled: true,
    environment: production ? "production" : "development",
    payload: {
      client: {
           javascript: {
              code_version: '1.0',
              source_map_enabled: true,
              guess_uncaught_frames: true
           }
      }
  }
});

// Vuex
const store = new Vuex.Store({
    modules: {
        global: globalStoreModule,
        sessionReports: sessionReportsStoreModule,
        expenses: expensesStoreModule,
        documents: documentsStoreModule
    }
})
store.dispatch('getUser');

// Vue Router
const router = new VueRouter({
    routes: routes
})

// Global error handler to Rollbar
Vue.config.errorHandler = function (err) {
    console.error(err)
    Vue.rollbar.error(err);
};

// Load Vue app
new Vue({
    router,
    store,
    mixins: [app],
    components: {
    }
});
