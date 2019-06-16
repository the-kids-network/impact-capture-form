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
            Spark.put('/settings/password', this.form);
        }
    }
};

export {
    Component
};

export default Vue.component('update-password', Component);
