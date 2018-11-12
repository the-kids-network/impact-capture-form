Vue.component('update-profile-details', {
    props: ['user'],

    data() {
        return {
            form: new SparkForm({
                reminder_emails: ''
            })
        };
    },

    mounted() {
        this.form.reminder_emails = this.user.reminder_emails;
    },

    methods: {
        update() {
            Spark.put('/settings/profile/details', this.form)
                .then(response => {
                    Bus.$emit('updateUser');
                });
        }
    }
});