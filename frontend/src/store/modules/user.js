

export default {
    state: {
        user: null
    },
    getters: {
        user: state => {
            return state.user;
        }
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        }
    },
    actions: {

    }
}