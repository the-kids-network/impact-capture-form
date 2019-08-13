import SparkForm from '../forms/form';
import http from '../forms/http';

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

            registerForm: new SparkForm({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                terms: false
            })
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

        sendRegistration() {
            http.post('/register', this.registerForm)
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

export default Component;