import '../../bootstrap';
import router from './router';
import { createApp } from 'vue';
import { createStore } from 'vuex';
import Index from './Index.vue';
// Global components
import Loading from './components/Loading.vue';
import Input from './components/form/Input.vue';
import Message from './components/Message.vue';
// Creates the application to start mounting global components
var app = createApp(Index).use(router);
app.mixin({
    methods: {
        getAuthenticationToken: function() {
            return localStorage.getItem('token') ?? null;
        }
    }
});
// Adds the token to the axios requests IF SET
axios.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token') ?? null;
        if (token) {
            config.headers['Authorization'] = 'Bearer ' + token;
        }
        return config;
    }, (error) => {
        return Promise.reject(error);
    }
);
const store = createStore({
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
})
app.use(store);
// Global component attachments
app.component('index', Index);
app.component('loading', Loading);
app.component('vm-input', Input);
app.component('vm-message', Message);
// Gets the settings from the configuration file for cuztomisable
var response = await axios.get('/cuztomisable/settings');
app.config.globalProperties.$cuztomisableSettings = response.data ?? null;

app.mount('#app');