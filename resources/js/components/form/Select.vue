<template>
    <div class="form-input">
        <label class="form-label" v-if="label != null">{{ label }}</label>
        <select
            v-model="value"
            class="form-control form-select"
            :class="errorList.length > 0 ? 'is-invalid' : ''"
            :disabled="disabled"
            :placeholder="placeholder"
            @input="errorList = []">
            <option v-for="(item, itemIndex) in items" :value="item.value">{{ item.text }}</option>        
        </select>
        <ul v-if="!hideDetails" class="form-errors">
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
        errors: { type: [Array, Object], default: [] },
        disabled: { type: Boolean, default: false },
        placeholder: { type: String, default: '' },
        hideDetails: { type: Boolean, default: false },
        items: { type: [Array, Object], default: [] },
    }
}
</script>