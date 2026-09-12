<template>
    <div class="setting-form">
        <cz-loading v-if="loading" :loading="loading" :large="false" :full="false" />
        <cz-form v-show="!loading" ref="settingForm" :form="form" @save="save">
            <h3 class="card-title">Edit {{ label }}</h3>
            <cz-textarea
                label="Value"
                v-model="form.value"
                :errors="errors.value"
                :disabled="submitting"
                wysiwyg
                rows="6" />
            <div class="mt-4">
                <button type="submit"
                    @click="save"
                    class="button button--primary"
                    :class="{ 'mb-2 button--block': breakpoint('sm'), 'mb-0 button-width': !breakpoint('sm') }"
                    :disabled="submitting">Save</button>
                <button type="button"
                    class="button button--secondary"
                    :class="{ 'mb-2 button--block': breakpoint('sm'), 'ml-4 mb-0 button-width': !breakpoint('sm') }"
                    :disabled="submitting"
                    @click="close">Nevermind</button>
            </div>
        </cz-form>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            loading: false,
            submitting: false,
            errors: [],
            form: { value: '' },
        };
    },
    computed: {
        label: function() {
            return (this.settingKey ?? '').split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
        },
    },
    methods: {
        get: function() {
            this.loading = true;
            axios.get(`/settings/${this.settingKey}`).then(({ data }) => {
                this.form = { value: data.value ?? '' };
            }).catch(({ response }) => {
                if (response?.data?.message) {
                    this.$message.push({ text: response.data.message, color: 'danger' });
                }
            }).finally(() => {
                this.loading = false;
            });
        },
        save: function() {
            this.submitting = true;
            axios.post('/settings', { key: this.settingKey, value: this.form.value ?? '' }).then(({ data }) => {
                this.$message.push({ text: data.message });
                this.$emit('redraw');
                setTimeout(() => {
                    this.close();
                }, 1500);
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
                }, 1500);
            });
        },
        close: function() {
            this.$emit('close');
            setTimeout(() => {
                this.errors = [];
                this.form = { value: '' };
            }, 250);
        },
    },
    watch: {
        settingKey: {
            immediate: true,
            handler: function(key) {
                this.errors = [];
                this.form = { value: '' };
                if (key) {
                    this.get();
                }
            },
        },
    },
    props: {
        settingKey: { type: String, default: null },
    },
}
</script>
