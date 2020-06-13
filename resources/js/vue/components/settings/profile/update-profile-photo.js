import SparkForm from '../../forms/form'

const Component = {

    /**
     * The component's data.
     */
    data() {
        return {
            form: new SparkForm({})
        };
    },


    methods: {
        /**
         * Update the user's profile photo.
         */
        update(e) {
            e.preventDefault();

            if ( ! this.$refs.photo.files.length) {
                return;
            }

            var self = this;

            this.form.startProcessing();

            // We need to gather a fresh FormData instance with the profile photo appended to
            // the data so we can POST it up to the server. This will allow us to do async
            // uploads of the profile photos. We will update the user after this action.
            axios.post(`/users/${this.user.id}/photo`, this.gatherFormData())
                .then(
                    () => {
                        Bus.$emit('updateUser');
                        self.form.setSuccess(null);
                    },
                    (error) => {
                        self.form.setErrors(error.response.data.errors);
                    }
                );
        },

        remove(e) {
            e.preventDefault();
            var self = this;
            this.form.startProcessing();

            axios.delete(`/users/${this.user.id}/photo`, this.gatherFormData())
                .then(
                    () => {
                        Bus.$emit('updateUser');
                        self.form.setSuccess(null);
                    },
                    (error) => {
                        self.form.setErrors(error.response.data.errors);
                    }
                );
        },

        /**
         * Gather the form data for the photo upload.
         */
        gatherFormData() {
            const data = new FormData();
            data.append('photo', this.$refs.photo.files[0]);
            return data;
        }
    },


    computed: {

    }
};

export default Component;