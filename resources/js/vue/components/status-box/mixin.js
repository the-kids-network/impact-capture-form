export default {

    data: function() {
        return {
            errors: [],
            successes: []
        }
    },

    computed: {
        
    },

    methods: {
        scrollTo(refName) {
            var element = this.$refs[refName];
            var top = element.offsetTop;
            window.scrollTo(0, top);
        },

        clearStatus() {
            this.clearSuccesses();
            this.clearErrors();
        },

        clearErrors() {
            this.errors = [];
        },

        clearSuccesses() {
            this.successes = [];
        },

        addErrors(errors=[]) {
            this.errors.push(...errors)
            this.scrollTo('status-box')
        },

        addSuccesses(successes=[]) {
            this.successes.push(...successes)
            this.scrollTo('status-box')
        }
    }
};
