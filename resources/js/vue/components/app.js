import SparkForm from './forms/form'
import http from './forms/http'
import Swal from 'sweetalert2'

const App = {
    el: '#app',

    /**
     * The application's data.
     */
    data: {
        supportForm: new SparkForm({
            from: '',
            subject: '',
            message: ''
        })
    },

    watch : {
        user() {
            if (this.user) {
                this.supportForm.from = this.user.email;
            }
        }
    },

    /**
     * The component has been created by Vue.
     */
    created() {
        const vm = this;

        Bus.$on('updateUser', function () {
            vm.$store.dispatch('getUser')
        });

        Bus.$on('showSupportForm', function () {
            $('#modal-support').modal('show');

            setTimeout(() => {
                $('#support-subject').focus();
            }, 500);
        });
    },

    /**
     * Prepare the application.
     */
    mounted() {
        this.whenReady();
    },

    methods: {
        /**
         * Finish bootstrapping the application.
         */
        whenReady() {
            //
        },

        /**
         * Send a customer support request.
         */
        sendSupportRequest() {
            http.post('/support/email', this.supportForm)
                .then(() => {
                    $('#modal-support').modal('hide');

                    this.showSupportRequestSuccessMessage();

                    this.supportForm.subject = '';
                    this.supportForm.message = '';
                });
        },


        /**
         * Show an alert informing the user their support request was sent.
         */
        showSupportRequestSuccessMessage() {
            Swal.fire({
                title: 'Got It!',
                text: 'We have received your message and will respond soon!',
                icon: 'success',
                showConfirmButton: false,
                timer: 4000
            });
        }
    },

    computed: {
    
    }
};

export default App;