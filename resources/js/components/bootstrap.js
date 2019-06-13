
// Some weird logic to get forms and form helpers on
// context Spark object for use everywhere - looks to be pre-Vue and very hacky.
// TODO: rewrite this as Vue form component
require('./forms/bootstrap')

// Load layout Vue components used by everything in the common layout
require('./layout/navbar/navbar')


// Load all specific Vue components
require('./settings/settings')
require('./settings/profile')
require('./settings/profile/update-contact-information')
require('./settings/profile/update-profile-photo')
require('./settings/security')
require('./settings/security/update-password')
require('./register/register')

// Load Vue app
var app = new Vue({
    mixins: [require('./app')]
});