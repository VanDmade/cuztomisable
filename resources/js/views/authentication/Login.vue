<template>
    <div class="page">
        <vm-message :message="message"></vm-message>
        <h3 class="mb-6">Login</h3>
        <form @submit.prevent="login">
            <vm-input
                :label="usernameLabel"
                v-model="form.username"
                type="input"
                :errors="errors.username"
                :disabled="submitting"/>
            <vm-input
                label="Password"
                v-model="form.password"
                type="password"
                :errors="errors.password"
                :disabled="submitting" />
            <router-link class="login-link mb-5" :to="{ name: 'forgot' }">Forgot password?</router-link>
            <button type="submit" class="btn btn-primary btn-sm-full-width" :disabled="submitting">Login</button>
        </form>
        <router-link class="login-link new-account-link" :to="{ name: 'registration' }">New here? Create an account!</router-link>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            submitting: false,
            errors: [],
            form: {
                username: 'michaelvanderwerkerllc@gmail.com',
                password: 'HelloHello1!',
            },
            message: {
                text: '',
                error: false,
            },
        }
    },
    created: function() {
        const query = Object.assign({}, this.$route.query);
        if (typeof(query.message) !== 'undefined') {
            this.message = {
                text: query.message,
                error: query.type == 'error',
            }
            // Removes the message from the site
            this.$router.replace({ 'query': null });
        }
    },
    methods: {
        login: function() {
            this.submitting = true;
            this.errors = [];
            var formData = new FormData();
            formData.append('username', this.form.username ?? '');
            formData.append('password', this.form.password ?? '');
            axios.post('/login', formData).then(({ data }) => {
                if (data.multi_factor_authentication !== true) {
                    this.$store.commit('login', data.token);
                    this.message = {
                        text: data.message,
                        error: false,
                    };
                }
                if (data.multi_factor_authentication === true) {
                    setTimeout(() => {
                        // Redirect to the MFA page
                        this.$router.push({ name: 'mfa', params: { token: data.token }});
                    }, 1500);
                }
            }).catch(({ response }) => {
                if (response.data.errors) {
                    this.errors = response.data.errors;
                } else if (response.data.message) {
                    this.errors.username = [];
                    this.errors.username.push(response.data.message);
                }
            }).finally(() => {
                setTimeout(() => {
                    this.submitting = false;
                }, 1500);
            });
        },
    },
    computed: {
        usernameLabel: function() {
            try {
                var loginWith = this.$cuztomisableSettings.login_with ?? [];
                return loginWith.phone ?
                    (loginWith.email ? 'Email Address or Phone Number' : 'Phone Number') :
                    (loginWith.email ? 'Email Address' : 'Username');
            } catch (error) {
                return 'Email Address';
            }
        }
    }
}
</script>