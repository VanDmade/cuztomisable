<template>
    <cz-modal ref="termsModal" modal-width="600px" static>
        <h3 class="card-title">{{ terms?.version ?? 'Updated Terms & Conditions' }}</h3>
        <div class="terms-gate-content" v-html="terms?.content"></div>
        <div class="form-buttons">
            <button type="button" class="button button--primary button--block" :disabled="submitting || !terms" @click="accept">I Accept</button>
        </div>
    </cz-modal>
</template>
<script>
export default {
    data: function() {
        return {
            terms: null,
            submitting: false,
        };
    },
    computed: {
        needsToAccept: function() {
            return this.$store.getters.needsToAcceptTerms;
        },
    },
    watch: {
        needsToAccept: {
            immediate: true,
            handler: function(value) {
                if (value) {
                    this.load();
                } else {
                    this.$refs.termsModal?.close();
                }
            },
        },
    },
    methods: {
        load: async function() {
            try {
                const { data } = await axios.get('/terms/current');
                this.terms = data?.data?.terms ?? data?.terms ?? null;
                this.$nextTick(() => this.$refs.termsModal.open());
            } catch (error) {
                // Nothing published to show yet - stay closed rather than block on an empty modal
            }
        },
        accept: async function() {
            this.submitting = true;
            try {
                await this.$store.dispatch('acceptTerms');
            } finally {
                this.submitting = false;
            }
        },
    },
}
</script>
