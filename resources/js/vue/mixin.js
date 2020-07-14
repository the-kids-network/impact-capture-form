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

        isAdminUser() {
            return this.user && ('admin' === this.user.role)
        },

        isManagerUser() {
            return this.user && ('manager' === this.user.role)
        },

        isInternalUser() {
            return this.isAdminUser || this.isManagerUser
        }
    },

    created() {
    },

    methods: {
        
    }
};
