import LocalStorage from 'vue-ls';
import VueSessionStorage from 'vue-sessionstorage'
import VModal from 'vue-js-modal'

import app from './app';
import statusBox from './status-box/root';
import navbar from './layout/navbar/navbar';
import settings from './settings/settings';
import profile from './settings/profile';
import updateContactInformation from './settings/profile/update-contact-information';
import updateProfilePhoto from './settings/profile/update-profile-photo';
import security from './settings/security';
import updatePassword from './settings/security/update-password';
import register from './register/register';
import calendar from './calendar/calendar';
import documentUpload from './documents/upload';
import documents from './documents/root';
import sessionReportEdit from './session-reports/edit'

// Load all specific globally registered Vue components
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
Vue.component('session-report-editor', sessionReportEdit)

// Load Vue app
Vue.use(LocalStorage, {
    namespace: 'tkn',
    name: 'ls',
    storage: 'local'
  }
);
Vue.use(VueSessionStorage)

Vue.use(VModal, { dynamic: true, dynamicDefaults: { clickToClose: false } })

new Vue({
    mixins: [app],
    components: {

    }
});