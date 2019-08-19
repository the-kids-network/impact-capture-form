const Component = {
    props: ['user'],

    /**
     * The component's data.
     */
    data() {
        return {
        }
    },

    created() {

    },

    mounted() {

    },

    methods: {
        showSupportForm() {
            Bus.$emit('showSupportForm');
        }
    }
}

export default Component