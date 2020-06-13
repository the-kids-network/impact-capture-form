import SparkForm from '../../forms/form'
import http from '../../forms/http'

const Component = {
    /**
     * The component's data.
     */
    data() {
        return {
            form: new SparkForm({
                current_password: '',
                password: '',
                password_confirmation: ''
            })
        };
    },

    methods: {
        /**
         * Update the user's password.
         */
        update() {
            http.put(`/users/${this.user.id}/password`, this.form);
        }
    }
};

export default Component;