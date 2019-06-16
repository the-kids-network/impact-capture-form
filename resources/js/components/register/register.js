const Component = {
    /**
     * Load mixins for the component.
     */
    mixins: [
    ],

    /**
     * The component's data.
     */
    data() {
        return {
            query: null,

            registerForm: $.extend(
                true, 
                new SparkForm({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false
                }), 
                Spark.forms.register
            )
        };
    },

    /**
     * The component has been created by Vue.
     */
    created() {
        this.query = URI(document.URL).query(true);
    },

    /**
     * Prepare the component.
     */
    mounted() {
        //
    },

    methods: {
        /**
         * Attempt to register with the application.
         */
        register() {
            return this.sendRegistration(); 
        },

        /*
         * After obtaining the Stripe token, send the registration to Spark.
         */
        sendRegistration() {
            Spark.post('/register', this.registerForm)
                .then(response => {
                    this.registerForm.resetFormData()
                });
        }
    },
    

    watch: {
        
    },

    computed: {
        
    }
};

export {
    Component
};

export default Vue.component('register', Component);
