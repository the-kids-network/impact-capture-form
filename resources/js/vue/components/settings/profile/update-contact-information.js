import SparkForm from '../../forms/form'
import http from '../../forms/http'

const Component = {
    props: ['user'],

    /**
     * The component's data.
     */
    data() {
        return {
            form: new SparkForm({
                    name: '',
                    email: ''
                })
        };
    },


    /**
     * Bootstrap the component.
     */
    mounted() {
        this.form.name = this.user.name;
        this.form.email = this.user.email;
    },

    methods: {
        /**
         * Update the user's contact information.
         */
        update() {
            http.put('/settings/contact', this.form)
                .then(() => {
                    Bus.$emit('updateUser');
                });
        }
    }
};

export default Component;
