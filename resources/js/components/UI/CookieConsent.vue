<template>
    <div v-if="visible" class="cz-cookie-consent">
        <p class="cz-cookie-consent-text" v-html="content"></p>
        <div class="cz-cookie-consent-actions">
            <button type="button" class="button button--secondary button--small" @click="choose('declined')">Decline</button>
            <button type="button" class="button button--primary button--small" @click="choose('accepted')">Accept</button>
        </div>
    </div>
</template>
<script>
import { needsToChooseCookieConsent, setCookieConsentChoice } from '../../utils/cookieConsent';

const DEFAULT_CONTENT = 'We use cookies to keep you signed in and improve your experience. By continuing, you agree to our use of cookies. <a href="/privacy-policy" target="_blank" rel="noopener">Learn more</a>';

export default {
    data: function() {
        return {
            visible: false,
            content: DEFAULT_CONTENT,
            updatedAt: null,
        };
    },
    created: async function() {
        try {
            const { data } = await axios.get('/settings/cookie_message');
            const payload = data?.data ?? data ?? {};
            this.content = payload.value || DEFAULT_CONTENT;
            this.updatedAt = payload.updated_at ?? null;
        } catch (error) {
            // No message set up yet, or the endpoint isn't reachable - fall back to the default
        }
        this.visible = needsToChooseCookieConsent(this.updatedAt);
    },
    methods: {
        choose: function(choice) {
            this.visible = false;
            setCookieConsentChoice(choice, this.updatedAt);
        },
    },
}
</script>
