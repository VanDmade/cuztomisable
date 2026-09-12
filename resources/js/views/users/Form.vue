<template>
    <div class="page" :class="{ 'container': breakpoint('lg'), 'container-fluid': breakpoint('md') || breakpoint('sm') }">
        <cz-loading :loading="loading || $store.state.loading"></cz-loading>
        <template v-if="!loading">
            <div class="card pa-6 mb-4 cz-user-summary">
                <cz-image
                    :src="imageUrl"
                    alt="User Image"
                    img-class="cz-image cz-user-summary-image"
                    v-model="form.image"
                    uploader />
                <div class="cz-user-summary-info">
                    <h5 class="card-title mb-1">{{ form.name || 'New User' }}</h5>
                    <p class="note mb-0">{{ form.email || form.phone?.number || '—' }}</p>
                    <div class="cz-user-summary-meta">
                        <span v-if="form.locked" class="cz-user-badge cz-user-badge--danger">Locked</span>
                        <span v-if="form.mfa" class="cz-user-badge cz-user-badge--success">MFA Enabled</span>
                        <span class="note">Member since {{ form.created_at ? formatDate(form.created_at) : '—' }}</span>
                        <span class="note">Last login: {{ form.last_login_at ? formatDate(form.last_login_at) : 'Never' }}</span>
                    </div>
                </div>
            </div>
            <div class="cz-tabs mb-4">
                <button type="button"
                    class="cz-tab"
                    :class="{ active: tab === 'details' }"
                    @click="tab = 'details'">Details<span v-if="detailsDirty" class="cz-tab-dirty" title="Unsaved changes"></span></button>
                <button type="button"
                    v-if="isMineOrHasPermission('reset-user-passwords') || (isMineOrHasPermission('toggle-user-mfa') && $cuztomisable.multi_factor_authentication.enabled)"
                    class="cz-tab"
                    :class="{ active: tab === 'security' }"
                    @click="tab = 'security'">Security<span v-if="securityDirty" class="cz-tab-dirty" title="Unsaved changes"></span></button>
                <button type="button"
                    v-if="$store.getters.hasPermission('manage-user-roles-permissions')"
                    class="cz-tab"
                    :class="{ active: tab === 'access' }"
                    @click="tab = 'access'">Access<span v-if="accessDirty" class="cz-tab-dirty" title="Unsaved changes"></span></button>
                <button type="button"
                    v-if="isMineOrHasPermission('view-user-logins')"
                    class="cz-tab"
                    :class="{ active: tab === 'logins' }"
                    @click="tab = 'logins'">Login History</button>
            </div>
            <div v-show="tab === 'details'" class="card pa-6">
                <cz-form ref="userForm" :form="form" @save="save">
                    <h5 class="card-title">User Details</h5>
                    <h6 class="card-subtitle mb-6 text-muted">Basic information and account settings.</h6>
                    <cz-input
                        label="Name"
                        v-model="form.name"
                        type="text"
                        :errors="errors.name"
                        :disabled="submitting" />
                    <cz-input
                        v-if="!$cuztomisable.login_with.email && !$cuztomisable.login_with.phone"
                        label="Username"
                        v-model="form.username"
                        type="text"
                        :errors="errors.username"
                        :disabled="submitting" />
                    <cz-input
                        label="Email"
                        v-model="form.email"
                        type="email"
                        :errors="errors.email"
                        :disabled="submitting" />
                    <cz-phone
                        label="Phone"
                        v-model="form.phone"
                        :errors="errors.phone"
                        :disabled="submitting"
                        is-mobile
                        default />
                    <cz-address
                        v-if="$cuztomisable.registration.address !== false"
                        label="Address"
                        v-model="form.address"
                        :errors="errors.address"
                        :disabled="submitting"
                        :hasAddressTwo="$cuztomisable.registration.address.address_two"
                        :hasAddressThree="$cuztomisable.registration.address.address_three" />
                    <div class="form-buttons">
                        <button v-if="isMineOrHasPermission('manage-users')"
                            type="submit"
                            class="button button--primary"
                            :class="{ 'button--block': breakpoint('sm'), 'mr-4 button-width': !breakpoint('sm') }"
                            :disabled="submitting">Save Changes</button>
                        <button type="button" class="button button--secondary" :class="{ 'button--block': breakpoint('sm'), 'button-width': !breakpoint('sm') }" @click="goBack()" :disabled="submitting">Go Back</button>
                    </div>
                </cz-form>
            </div>
            <div v-show="tab === 'security'" class="card pa-6">
                <component
                    v-if="isMineOrHasPermission('toggle-user-mfa') && $cuztomisable.multi_factor_authentication.enabled"
                    is="user-mfa-form"
                    v-model="form.mfa"
                    v-on:message="message"
                    :user="form?.id"
                    class="mb-6"></component>
                <hr v-if="isMineOrHasPermission('toggle-user-mfa') && $cuztomisable.multi_factor_authentication.enabled && isMineOrHasPermission('reset-user-passwords')" class="mb-6">
                <component
                    v-if="isMineOrHasPermission('reset-user-passwords')"
                    is="user-password-form"
                    ref="user-password-form"
                    v-on:message="message"
                    :user="form?.id"
                    :admin="$store.state.user?.admin"
                    :change-password-sent-at="form.change_password_sent_at"></component>
            </div>
            <div v-show="tab === 'access'" class="card pa-6">
                <component
                    v-if="$store.getters.hasPermission('manage-user-roles-permissions') && form.id"
                    is="user-security-form"
                    ref="user-security-form"
                    v-on:message="message"
                    :user="form?.id"></component>
            </div>
            <div v-show="tab === 'logins'" class="card pa-6">
                <component
                    v-if="isMineOrHasPermission('view-user-logins') && form.id"
                    is="user-login-form"
                    ref="user-login-form"
                    v-on:message="message"
                    :user="form?.id"
                    :admin="$store.state.user?.admin"></component>
            </div>
        </template>
    </div>
</template>
<script>
import Password from './Password.vue';
import MFA from './MFA.vue';
import RecentLogin from './RecentLogin.vue';
import Security from './Security.vue';
export default {
    data: function() {
        return {
            loading: false,
            submitting: false,
            errors: [],
            imageUrl: this.$url+'profile.png',
            tab: 'details',
            form: {},
            // Snapshot of the Details fields right after load, so editing them (without saving)
            // can be detected and flagged on the tab.
            detailsSnapshot: null,
        };
    },
    methods: {
        get: function() {
            let id = this.$route.params.id;
            axios.get(`/user/${id}`).then(({ data }) => {
                this.form = this.clone(data.user);
                this.snapshotDetails();
            }).catch((error) => {

            }).finally(() => {
                setTimeout(() => {
                    this.loading = false;
                }, 1000);
            });
        },
        snapshotDetails: function() {
            const { name, username, email, phone, address } = this.form;
            this.detailsSnapshot = JSON.stringify({ name, username, email, phone, address });
        },
        save: function() {
            let id = this.$route.params.id;
            let formData = new FormData();
            formData.append('name', this.form.name ?? '');
            formData.append('image', this.form.image ?? '');
            if (!this.$cuztomisable.login_with.email && !this.$cuztomisable.login_with.phone) {
                formData.append('username', this.form.username ?? '');
            }
            formData.append('email', this.form.email ?? '');
            formData.append('phone', this.form.phone?.number ?? '');
            formData.append('country_code', this.form.phone?.country_code ?? '');
            if (this.$cuztomisable.registration.address !== false) {
                formData.append('address', this.form.address?.address);
                formData.append('address_two', this.form.address?.address_two);
                formData.append('address_three', this.form.address?.address_three);
                formData.append('city', this.form.address?.city);
                formData.append('state_or_province', this.form.address?.state_or_province);
                formData.append('zip_or_postal_code', this.form.address?.zip_or_postal_code);
                formData.append('country', this.form.address?.country);
            }
            this.submitting = true;
            formData = this.cleanFormData(formData);
            axios.post(`/user/${id}`, formData).then(({ data }) => {
                this.$message.push({ text: data.message });
                this.snapshotDetails();
            }).catch(({ response }) => {
                if (response?.data?.errors) {
                    this.errors = response.data.errors;
                    this.errors['address'] = {
                        address: this.errors['address'] ?? '',
                        address_two: this.errors['address_two'] ?? '',
                        address_three: this.errors['address_three'] ?? '',
                        city: this.errors['city'] ?? '',
                        state_or_province: this.errors['state_or_province'] ?? '',
                        zip_or_postal_code: this.errors['zip_or_postal_code'] ?? '',
                        country: this.errors['country'] ?? '',
                    };
                }
                if (response?.data?.message) {
                    this.$message.push({ text: response.data.message, color: 'danger' });
                }
            }).finally(() => {
                setTimeout(() => {
                    this.submitting = false;
                }, 1500);
            });
        },
        message: function(message) {
            this.$message.push({ ...(message ?? {}), color: message?.error ? 'danger' : message?.color });
        },
        handleUserChange: function(user) {
            if (typeof(this.form.id) == 'undefined') {
                let id = this.$route.params.id;
                if (id == '' || typeof(id) == 'undefined') {
                    this.form = user ?? {};
                    this.snapshotDetails();
                    setTimeout(() => {
                        this.loading = false;
                    }, 1000);
                } else {
                    this.get();
                }
            }
        },
        isMineOrHasPermission: function(slug) {
            return this.$store.getters.hasPermission(slug) || typeof(this.$route.params.id) == 'undefined';
        }
    },
    computed: {
        detailsDirty: function() {
            if (!this.detailsSnapshot) {
                return false;
            }
            const { name, username, email, phone, address } = this.form;
            return JSON.stringify({ name, username, email, phone, address }) !== this.detailsSnapshot;
        },
        securityDirty: function() {
            return this.$refs['user-password-form']?.dirty ?? false;
        },
        accessDirty: function() {
            return this.$refs['user-security-form']?.dirty ?? false;
        },
    },
    watch: {
        '$store.state.user': {
            handler: function(user) {
                this.handleUserChange(user);
            }
        },
        '$route.params.id': {
            immediate: true,
            handler: function(id) {
                this.errors = [];
                this.loading = true;
                let user = {};
                if (this.form.id != id) {
                    this.form = {};
                }
                if (typeof(id) == 'undefined') {
                    user = this.$store.state.user;
                } else {
                    this.form = {};
                }
                this.handleUserChange(user);
            }
        }
    },
    props: {
        id: { type: [String, Number], default: null },
    },
    components: {
        'user-login-form': RecentLogin,
        'user-security-form': Security,
        'user-mfa-form': MFA,
        'user-password-form': Password,
    }
};
</script>
