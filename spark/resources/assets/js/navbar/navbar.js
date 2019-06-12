module.exports = {
    props: [
        'user'
    ],

    methods: {

        /**
         * Show the customer support e-mail form.
         */
        showSupportForm() {
            Bus.$emit('showSupportForm');
        }
    }
};
