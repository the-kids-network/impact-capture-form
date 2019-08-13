import app from './app'
import navbar from './layout/navbar/navbar';
import settings from './settings/settings';
import profile from './settings/profile';
import updateContactInformation from './settings/profile/update-contact-information';
import updateProfilePhoto from './settings/profile/update-profile-photo';
import security from './settings/security';
import updatePassword from './settings/security/update-password';
import register from './register/register';

// Load all specific globally registered Vue components
Vue.component('nav-bar', navbar)
Vue.component('settings', settings)
Vue.component('profile', profile)
Vue.component('update-contact-information', updateContactInformation)
Vue.component('update-profile-photo', updateProfilePhoto)
Vue.component('security', security)
Vue.component('update-password', updatePassword)
Vue.component('register', register)

// Load Vue app
new Vue({
    mixins: [app]
});