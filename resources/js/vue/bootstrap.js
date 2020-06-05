import Vue from 'vue';
import Vuex from 'vuex'

import LocalStorage from 'vue-ls';
import VueSessionStorage from 'vue-sessionstorage'
import VModal from 'vue-js-modal'
import globalMixins from './mixin';

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
import documentUpload from './components/documents/upload';
import documents from './components/documents/root';
import sessionReports from './components/session-reports/index'
import sessionReportEdit from './components/session-reports/edit'

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
Vue.component('document-upload', documentUpload)
Vue.component('documents', documents)
Vue.component('session-reports', sessionReports)
Vue.component('session-report-editor', sessionReportEdit)

// Vue plugins#
Vue.use(Vuex)

Vue.use(LocalStorage, {
    namespace: 'tkn',
    name: 'ls',
    storage: 'local'
  }
);
Vue.use(VueSessionStorage)

Vue.use(VModal, { dynamic: true, dynamicDefaults: { clickToClose: false } })

// Load Vue app
new Vue({
    mixins: [app],
    components: {
    }
});

