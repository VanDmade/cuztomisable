<template>
    <div class="page">
        <h4>Portal</h4>
        {{ $store.state.user }}
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            loading: false,
        }
    },
    created: function() {

    },
    methods: {

    },
}
</script>