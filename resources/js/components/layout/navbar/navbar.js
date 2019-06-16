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

export {
    Component
}

export default Vue.component('nav-bar', Component);
