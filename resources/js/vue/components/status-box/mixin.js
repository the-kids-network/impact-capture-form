import { mapErrors } from "../../utils/error";

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
        scrollToStatus() {
            const statusBox = this.$refs['status-box']
            const elem = statusBox.$refs['top-of-status']
            // offset to account for nav bar
            this.$scrollTo(elem, {offset: -75})          
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

        addError(e) {
            this.errors.push(e)
        },

        addSuccess(mess) {
            this.successes.push(mess)
        },

        async try(actionName, toExecute, {handleError=true, handleSuccess=false, scroll=true}={}) {
            try {
                await toExecute()
                if (handleSuccess) {
                    this.addSuccess(`Success --> ${actionName}`)
                    if (scroll) this.scrollToStatus()
                }
            } catch (e) {
                e = mapErrors({e, actionName})
                if (handleError) {
                    this.addError(e)
                    if (scroll) this.scrollToStatus()
                }
            }
        }
    }
};
