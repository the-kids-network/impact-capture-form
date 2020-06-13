// Global mixin applied to all loaded vue components
export default {
    data () {
        return {
        }
    },

    computed: {
        user: {
            get: function () {
                return this.$store.state.global.user
            },
            set: function (user) {
                this.$store.global.commit('setUser', user)
            }
        },

        isInternalUser() {
            return this.user && ('admin' === this.user.role || 'manager' === this.user.role)
        }
    },

    created() {
    },

    methods: {
        
    }
};
