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
        scrollTo(position) {
            const statusBox = this.$refs['status-box']

            if (position === 'bottom') {
                const elem = statusBox.$refs['bottom-of-status']
                this.$scrollTo(elem)          
            } else {
                const elem = statusBox.$refs['top-of-status']
                // offset to account for nav bar
                this.$scrollTo(elem, {offset: -75})          
            }
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

        setErrors({errs=[], scrollToPos='top'}) {
            this.errors = errs
            this.scrollTo(scrollToPos)
        },

        addErrors({errs=[], scrollToPos='top'}) {
            this.errors.push(...errs)
            this.scrollTo(scrollToPos)
        },

        setSuccesses({succs=[], scrollToPos='top'}) {
            this.successes = succs
            this.scrollTo(scrollToPos)
        },

        addSuccesses({succs=[], scrollToPos='top'}) {
            this.successes.push(...succs)
            this.scrollTo(scrollToPos)
        }
    }
};
