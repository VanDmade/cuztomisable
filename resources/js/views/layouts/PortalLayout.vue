<template>
    <div id="portal-layout" class="layout">
        <loading v-if="loading"></loading>
        <div v-else>
            <nav class="navbar navbar-expand-lg bg-primary mb-6 shadow" data-bs-theme="dark">
                <div class="container-fluid">
                    <router-link class="navbar-brand" :to="{ name: 'portal' }">Cuztomisable</router-link>
                    <button class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navigation"
                        aria-controls="navigation"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navigation">
                        <div class="navbar-nav me-auto">
                            <span v-if="screenSize == 'medium'" class="navbar-text text-white h4 mb-0 mt-3">{{ user.name }}</span>
                            <hr v-if="screenSize == 'medium'">
                            <router-link class="nav-link" :class="$route.name == 'portal' ? 'active' : ''" :to="{ name: 'portal' }">Portal</router-link>
                        </div>
                        <div class="navbar-nav ms-auto" >
                            <span v-if="screenSize == 'large'" class="navbar-text text-white pr-6 mr-4" style="border-right: 1px solid #fff">{{ user.name }}</span>
                            <span class="nav-link d-inline-flex align-items-center text-white" href="#" @click="logout">
                                <span v-if="screenSize == 'large'" class="material-icons">logout</span>
                                <span v-else>Logout</span>
                            </span>
                        </div>
                    </div>
                </div>
            </nav>
            <slot></slot>
        </div>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            user: [],
            navbarToggle: false,
            loading: true,
            screenSize: 'large',
        }
    },
    created: function() {
        // Checks to see if the user is logged in and needs to be directed to the portal
        if (!this.$route.meta.require_authentication && this.$store.state.authenticated) {
            this.$router.push({ name: 'portal' });
        }
        this.me();
    },
    mounted: function() {
        this.onResize();
        this.$nextTick(() => {
            window.addEventListener('resize', this.onResize);
        })
    },
    beforeDestroy: function() { 
        window.removeEventListener('resize', this.onResize); 
    },
    methods: {
        me: function() {
            axios.get('/me').then(({ data }) => {
                this.user = data.user;
                this.$store.commit('setUser', data.user);
            }).catch(({ response }) => {
                this.logout(response.data.message);
            }).finally(() => {
                this.loading = false;
            });
        },
        onResize: function() {
            this.screenSize = window.innerWidth <= 992 ? 'medium' : 'large';
        },
        logout: function(message) {
            this.$store.commit('logout');
            this.$router.push({ name: 'login', query: { message: message != null ? message : '', type: 'error' } });
        }
    },
}
</script>