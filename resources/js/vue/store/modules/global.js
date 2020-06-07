const module = {
    state: {
        user: null
    },
    mutations: {
        setUser(state, user) {
            state.user = user
        },
    },
    getters: {
    
    },
    actions: {
        async getUser(context) {
            const user = (await axios.get('/api/user/current')).data
            context.commit('setUser', user)
        }
    }
}

export default module