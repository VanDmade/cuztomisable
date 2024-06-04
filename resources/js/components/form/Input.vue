<template>
    <div class="form-input">
        <label class="form-label" v-if="label != null">{{ label }}</label>
        <input
            :type="type"
            v-model="value"
            class="form-control"
            :class="errorList.length > 0 ? 'is-invalid' : ''"
            :disabled="disabled"
            @input="errorList = []">
        <ul v-if="!hideDetails" class="form-errors" style=";">
            <li v-for="(error, i) in errorList" :key="'error-'+i" class="form-error">{{ error }}</li>
        </ul>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            errorList: [],
        }
    },
    computed: {
        value: {
            get: function () {
                return this.modelValue;
            },
            set: function (value) {
                this.$emit('update:modelValue', value);
            }
        }
    },
    watch: {
        errors: {
            immediate: true,
            handler: function(errors) {
                this.errorList = errors;
            },
        },
    },
    props: {
        modelValue: { type: [String, Number], default: '' },
        label: { type: String, default: null },
        type: { type: String, default: 'input' },
        errors: { type: [Array, Object], default: [] },
        disabled: { type: Boolean, default: false },
        hideDetails: { type: Boolean, default: false },
    }
}
</script>