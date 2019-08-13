const Component = {
    props: ['user'],

    mixins: [require('./../mixins/tab-state')],

    data() {
        return {
           
        };
    },

    mounted() {
        this.usePushStateForTabs('.spark-settings-tabs');
    }
};

export default Component;
