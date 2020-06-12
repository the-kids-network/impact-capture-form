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
        scrollTo(statusBoxRef, position) {
            if (position === 'bottom') {
                const elem = this.$refs[statusBoxRef].$refs['bottom-of-status']
                this.$scrollTo(elem)          
            } else {
                const elem = this.$refs[statusBoxRef].$refs['top-of-status']
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

        addErrors({errs=[], scrollTo='status-box', scrollToPos='top'}) {
            this.errors.push(...errs)
            this.scrollTo(scrollTo, scrollToPos)
        },

        addSuccesses({succs=[], scrollTo='status-box', scrollToPos='top'}) {
            this.successes.push(...succs)
            this.scrollTo(scrollTo, scrollToPos)
        }
    }
};
