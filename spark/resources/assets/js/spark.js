/**
 * Export the root Spark application.
 */
module.exports = {
    el: '#spark-app',

    /**
     * The application's data.
     */
    data: {
        user: Spark.state.user,
        supportForm: new SparkForm({
            from: '',
            subject: '',
            message: ''
        })
    },


    /**
     * The component has been created by Vue.
     */
    created() {
        var self = this;

        Bus.$on('updateUser', function () {
            self.getUser();
        });

        Bus.$on('showSupportForm', function () {
            if (self.user) {
                self.supportForm.from = self.user.email;
            }

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

        /*
         * Get the current user of the application.
         */
        getUser() {
            axios.get('/user/current')
                .then(response => {
                    this.user = response.data;
                });
        },

        /**
         * Send a customer support request.
         */
        sendSupportRequest() {
            Spark.post('/support/email', this.supportForm)
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
            swal({
                title: 'Got It!',
                text: 'We have received your message and will respond soon!',
                type: 'success',
                showConfirmButton: false,
                timer: 2000
            });
        }
    },


    computed: {
    
    }
};
