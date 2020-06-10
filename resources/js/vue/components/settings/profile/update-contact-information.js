import SparkForm from '../../forms/form'
import http from '../../forms/http'

const Component = {

    data() {
        return {
            form: new SparkForm(
                {
                    name: '',
                    email: ''
                }
            )
        };
    },

    watch: {
        user() {
            if (this.user) {
                this.form.name = this.user.name;
                this.form.email = this.user.email;
            }
        }
    },

    methods: {
        /**
         * Update the user's contact information.
         */
        update() {
            http.put(`/users/${this.user.id}/contact`, this.form)
                .then(() => {
                    Bus.$emit('updateUser');
                });
        }
    }
};

export default Component;
