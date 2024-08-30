import { createStore } from 'vuex';

export default createStore({
    state: function() {
        return {
            authenticated: localStorage.getItem('token') ? true : false,
            user: [],
        }
    },
    mutations: {
        login: function(state, token) {
            state.authenticated = true;
            localStorage.setItem('token', token);
        },
        logout: function(state) {
            state.authenticated = false;
            state.user = [];
            localStorage.removeItem('token');
        },
        setUser: function(state, user) {
            state.authenticated = true;
            state.user = user;
        },
    },
});