<template>
    <div id="login-layout" class="layout">
        <div id="login-description" class="vertical-center-container">
            <div class="vertical-center">
                <div class="vertical-center-wrapper">
                    <div id="login-content" class="vertical-center-content">
                        <h1 id="login-title">Welcome to Cuztomisable</h1>
                        <p id="login-message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <router-link class="btn btn-light new-account-link" :to="{ name: 'registration' }">New here? Create an account!</router-link>
                    </div>
                </div>
            </div>
        </div>
        <div id="login-pages" class="shadow vertical-center-container">
            <div class="vertical-center">
                <div class="vertical-center-wrapper">
                    <div class="vertical-center-content">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: function() {
        return {

        }
    },
    created: function() {
        // Checks to see if the user is logged in and needs to be directed to the portal
        if (!this.$route.meta.require_authentication && this.$store.state.authenticated) {
            this.$router.push({ name: 'portal' });
            return;
        }
    },
    methods: {

    },
    
}
</script>