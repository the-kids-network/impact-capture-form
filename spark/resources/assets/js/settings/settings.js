module.exports = {
    props: ['user'],


    /**
     * Load mixins for the component.
     */
    mixins: [require('./../mixins/tab-state')],


    /**
     * The component's data.
     */
    data() {
        return {
           
        };
    },


    /**
     * Prepare the component.
     */
    mounted() {
        this.usePushStateForTabs('.spark-settings-tabs');
    }
};
