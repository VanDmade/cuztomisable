import './bootstrap';
import router from './router';
import { createApp } from 'vue';
import store from './store';
import Index from './Index.vue';
// Global components
import Loading from './components/Loading.vue';
import Input from './components/form/Input.vue';
import Message from './components/Message.vue';
// Creates the application to start mounting global components
var app = createApp(Index).use(router);
app.mixin({
    data: function() {
        return {
            width: window.innerWidth,
        }
    },
    created: function() {
        window.addEventListener('resize', this.onResize);
    },
    destroyed: function() {
        window.removeEventListener('resize', this.onResize);
    },
    methods: {
        getAuthenticationToken: function() {
            return localStorage.getItem('token') ?? null;
        },
        breakpoint: function(type) {
            if (type == 'md' && this.width >= 768) {
                return true;
            } else if (type == 'sm' && this.width < 768) {
                return true;
            }
            return false;
        },
        onResize: function() {
            this.width = window.innerWidth;
        },
    },
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