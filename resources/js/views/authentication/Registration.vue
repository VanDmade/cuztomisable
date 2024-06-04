<template>
    <div class="page">
        <div class="mb-3">
            <label for="email-address" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email-address" v-model="form.email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" v-model="form.password">
        </div>
        <requirements :password="form.password" class="mt-4 mb-4"/>
        <button type="button" @click="save" class="btn btn-primary btn-sm-full-width">Register</button>
        <router-link class="login-link mt-4" :to="{ name: 'login' }">Already have an account?</router-link>
    </div>
</template>
<script>
import PasswordRequirements from '../../components/PasswordRequirements.vue';
export default {
    data: function() {
        return {
            form: {
                email: '',
                password: '',
            }
        }
    },
    methods: {
        save: function() {

        },
    },
    components: {
        'requirements': PasswordRequirements,
    }
}
</script>