<template>
    <div class="loading-container">
        Loading...
    </div>
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
        full: { type: Boolean, default: true },
    }
}
</script>
<style>
    .loading-container {
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 999;
        background: #fff;
    }
</style>