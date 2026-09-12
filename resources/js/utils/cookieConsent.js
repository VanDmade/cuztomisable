// Reusable check for any future non-essential script (analytics, a chat widget, etc.) to call
// before it loads - e.g. `if (hasConsentedToCookies()) { loadAnalytics(); }`. The session/CSRF/
// auth token cookies this app itself relies on are strictly necessary and aren't gated by this -
// there's nothing to actually block on decline until a non-essential script exists.
const STORAGE_KEY = 'cuztomisable_cookie_consent';

function read() {
    try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY));
    } catch (error) {
        return null;
    }
}

export function getCookieConsentChoice() {
    return read()?.choice ?? null;
}

export function hasConsentedToCookies() {
    return getCookieConsentChoice() === 'accepted';
}

export function setCookieConsentChoice(choice, updatedAt) {
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify({ choice, updated_at: updatedAt ?? null }));
    } catch (error) {
        // Private browsing / storage disabled - nothing we can persist
    }
}

export function needsToChooseCookieConsent(updatedAt) {
    const stored = read();
    return !stored || stored.updated_at !== (updatedAt ?? null);
}
