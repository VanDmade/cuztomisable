<template>
    <div id="change-password-form">
        <cz-form ref="changePasswordForm" :form="form"
            @save="submit">
            <div v-if="admin" id="password-admin-controls" class="cz-password-admin-row">
                <div>
                    <h6 class="card-title mb-1">Admin Controls</h6>
                    <p class="note mb-0">
                        {{ isSelf
                            ? "Sending yourself a temporary password would force your own active session to change it on next login - use Change Password below instead."
                            : "Sends the user a temporary password by email, which they'll be required to change on their next login." }}
                    </p>
                </div>
                <button type="submit"
                    @click="submitAction = 'send'"
                    class="button button--primary"
                    :disabled="isSelf || submitting || sendCooldownRemaining > 0"
                    :title="isSelf ? 'Not available for your own account' : null">{{ sendCooldownRemaining > 0 ? `Sent - retry in ${formatCooldown(sendCooldownRemaining)}` : 'Send' }}</button>
            </div>
            <hr v-if="admin && isSelf" class="mt-4 mb-4">
            <div v-if="isSelf" id="password-controls">
                <h3 class="card-title">Change Password</h3>
                <h6 class="card-subtitle mb-2 text-muted">Set a new password for this account.</h6>
                <cz-input
                    label="Current Password"
                    v-model="form.current"
                    type="password"
                    autocomplete="current-password"
                    :errors="errors.current"
                    :disabled="submitting" />
                <cz-input
                    label="New Password"
                    v-model="form.new"
                    type="password"
                    autocomplete="new-password"
                    :errors="errors.new"
                    :disabled="submitting" />
                <requirements :password="form.new" v-on:completed="completed" class="mb-4"></requirements>
                <button type="submit"
                    @click="submitAction = 'change'"
                    class="button button--primary"
                    :disabled="submitting || !passwordRequirementsMet">Change</button>
            </div>
        </cz-form>
    </div>
</template>
<script>
import PasswordRequirements from '../../components/PasswordRequirements.vue';
function initialize() {
    return {
        submitting: false,
        submitAction: 'change',
        passwordRequirementsMet: false,
        errors: [],
        form: {
            current: '',
            new: '',
        },
    };
}
export default {
    data: function() {
        return {
            ...initialize(),
            // Local override so the cooldown starts immediately after a successful send rather
            // than waiting on the parent to refetch the user. Server-driven (change_password_sent_at
            // + config resend_after) so a page refresh doesn't reset it either.
            sentAtOverride: null,
            now: Date.now(),
            tick: null,
        };
    },
    mounted: function() {
        this.tick = setInterval(() => { this.now = Date.now(); }, 1000);
    },
    beforeUnmount: function() {
        clearInterval(this.tick);
    },
    methods: {
        reset: function() {
            Object.assign(this.$data, initialize());
        },
        submit: function() {
            this.submitting = true;
            this.errors = [];
            let formData = new FormData();
            let url;
            if (this.submitAction === 'change') {
                formData.append('current', this.form.current ?? '');
                formData.append('new', this.form.new ?? '');
                url = '/user/change/password';
            } else {
                url = `/user/${this.user}/send/password`;
            }
            axios.post(url, formData).then(({ data }) => {
                this.$message.push({ text: data.message });
                if (this.submitAction === 'change') {
                    this.reset();
                } else {
                    this.sentAtOverride = new Date().toISOString();
                }
            }).catch(({ response }) => {
                if (response?.data?.errors) {
                    this.errors = response.data.errors;
                }
                if (response?.data?.message) {
                    this.$message.push({ text: response.data.message, color: 'danger' });
                }
            }).finally(() => {
                setTimeout(() => {
                    this.submitting = false;
                }, 1000);
            });
        },
        completed: function(value) {
            this.passwordRequirementsMet = value;
        },
        formatCooldown: function(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return m > 0 ? `${m}m ${s}s` : `${s}s`;
        },
    },
    computed: {
        isSelf: function() {
            return this.user == this.$store.state.user.id;
        },
        dirty: function() {
            return !!(this.form.current || this.form.new);
        },
        sendCooldownRemaining: function() {
            const sentAt = this.sentAtOverride ?? this.changePasswordSentAt;
            if (!sentAt) {
                return 0;
            }
            const resendAfter = this.$cuztomisable?.administrator?.temporary_password?.resend_after ?? 300;
            const elapsed = Math.floor((this.now - new Date(sentAt).getTime()) / 1000);
            return Math.max(0, resendAfter - elapsed);
        },
    },
    props: {
        admin: { type: Boolean, default: false },
        user: { type: [Number, String], default: null },
        changePasswordSentAt: { type: String, default: null },
    },
    components: {
        'requirements': PasswordRequirements,
    }
}
</script>