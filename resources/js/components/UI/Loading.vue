<template>
    <transition name="fade">
        <div v-if="loading" :class="['cz-loading', { large, 'cz-loading-full': full }, full ? 'cz-loading-bg-'+background : null]">
            <div class="cz-spinner spinner-border cz-bootstrap-spinner text-primary" :class="{ 'spinner-border-sm': !large }" role="status" aria-live="polite">
                <span class="visually-hidden">{{ message }}</span>
            </div>
            <p class="cz-loading-text">{{ message }}</p>
        </div>
    </transition>
</template>
<script>
export default {
    data: function() {
        return {
            
        }
    },
    methods: {

    },
    props: {
        loading: { type: Boolean, required: true },
        full: { type: Boolean, default: true },
        large: { type: Boolean, default: true },
        message: { type: String, default: 'Loading...' },
        // Only relevant when full is true - a contextual (non-full) loading indicator always
        // stays transparent so it blends into whatever modal/card it's sitting in.
        background: {
            type: String,
            default: 'white',
            validator: (value) => ['white', 'color', 'overlay'].includes(value),
        },
    }
}
</script>