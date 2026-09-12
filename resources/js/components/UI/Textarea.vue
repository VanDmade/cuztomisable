<template>
    <div v-if="wysiwyg" class="cz-form-input cz-form-wysiwyg" :class="{ 'is-invalid': errorList.length > 0 }">
        <label v-if="label != null && label != ''" :for="id" class="form-label cz-form-label cz-form-label-static">{{ label }}</label>
        <div class="cz-wysiwyg-toolbar">
            <button type="button" tabindex="-1" title="Bold" @mousedown.prevent="exec('bold')"><b>B</b></button>
            <button type="button" tabindex="-1" title="Italic" @mousedown.prevent="exec('italic')"><i>I</i></button>
            <button type="button" tabindex="-1" title="Underline" @mousedown.prevent="exec('underline')"><u>U</u></button>
            <span class="cz-wysiwyg-divider"></span>
            <button type="button" tabindex="-1" title="Bullet list" @mousedown.prevent="exec('insertUnorderedList')">&bull; List</button>
            <button type="button" tabindex="-1" title="Numbered list" @mousedown.prevent="exec('insertOrderedList')">1. List</button>
            <span class="cz-wysiwyg-divider"></span>
            <button type="button" tabindex="-1" title="Add link" @mousedown.prevent="addLink">Link</button>
            <button type="button" tabindex="-1" title="Remove formatting" @mousedown.prevent="exec('removeFormat')">Clear</button>
        </div>
        <div
            ref="editor"
            class="form-control cz-form-control cz-wysiwyg-editor"
            :class="[{ 'is-invalid': errorList.length > 0 }, inputClass]"
            :style="{ 'min-height': height }"
            contenteditable
            :aria-disabled="disabled || readonly"
            @input="onEditorInput"
            @blur="errorList = []"></div>
        <ul v-if="!hideDetails" class="form-errors cz-form-errors mb-2">
            <li v-for="(error, i) in errorList" :key="id+'-error-'+i" class="form-error cz-form-error">{{ error }}</li>
        </ul>
    </div>
    <div v-else class="form-floating cz-form-input cz-form-textarea"
        :class="{ 'cz-no-label': label == null || label == '' }">
        <textarea
            v-model="value"
            :id="id"
            class="form-control cz-form-control"
            :class="[{ 'is-invalid': errorList.length > 0, 'empty': value == '' || value == null }, inputClass]"
            :disabled="disabled"
            :readonly="readonly"
            :placeholder="placeholder"
            :maxlength="maxlength"
            :style="{ 'height': height }"
            @input="errorList = []"></textarea>
        <label v-if="label != null && label != ''" :for="id" class="form-label cz-form-label">{{ label }}</label>
        <ul v-if="!hideDetails" class="form-errors cz-form-errors mb-2">
            <li v-for="(error, i) in errorList" :key="id+'-error-'+i" class="form-error cz-form-error">{{ error }}</li>
        </ul>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            id: 'cz-textarea_'+Math.random().toString(16).slice(2),
            // Calculates the height based on the total rows to allow for floating labels with ease
            height: ((parseInt(this.rows) + 1) * 25) + 'px',
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
        modelValue: function(value) {
            // Only syncs from outside (e.g. the value arriving after an async fetch) - if this
            // fired because of the editor's own @input, its innerHTML already matches and this
            // is a no-op, so typing never gets its cursor position clobbered.
            if (this.wysiwyg && this.$refs.editor && this.$refs.editor.innerHTML !== (value ?? '')) {
                this.$refs.editor.innerHTML = value ?? '';
            }
        },
    },
    mounted: function() {
        if (this.wysiwyg && this.$refs.editor) {
            this.$refs.editor.innerHTML = this.modelValue ?? '';
            this.$refs.editor.contentEditable = !(this.disabled || this.readonly);
        }
    },
    methods: {
        // A dependency-free rich text editor (contenteditable + the browser's own execCommand)
        // rather than pulling in a full editor library - enough for basic formatting on things
        // like the cookie message without adding a new npm dependency to every install.
        exec: function(command, value = null) {
            document.execCommand(command, false, value);
            this.$refs.editor.focus();
            this.onEditorInput();
        },
        addLink: function() {
            const url = window.prompt('Link URL');
            if (url) {
                this.exec('createLink', url);
            }
        },
        onEditorInput: function() {
            this.errorList = [];
            this.$emit('update:modelValue', this.$refs.editor.innerHTML);
        },
    },
    props: {
        modelValue: { type: [String, Number], default: '' },
        label: { type: String, default: null },
        placeholder: { type: String, default: '' },
        rows: { type: [Number, String], default: 2 },
        maxlength: { type: Number, default: null },
        inputClass: { type: String, default: '' },
        errors: { type: [Array, Object], default: [] },
        disabled: { type: Boolean, default: false },
        readonly: { type: Boolean, default: false },
        hideDetails: { type: Boolean, default: false },
        // Renders a rich text (contenteditable) editor with a formatting toolbar instead of a
        // plain textarea, and treats modelValue/update:modelValue as HTML rather than plain text.
        wysiwyg: { type: Boolean, default: false },
    }
}
</script>
