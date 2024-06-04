<template>
    <div class="vm-messages">
        <div v-for="(item, index) in messages" class="vm-message-container">
            <div v-if="item.text != ''"
                class="vm-message shadow"
                :class="item.error ? 'vm-message-error' : 'vm-message-success'">{{ item.text }}</div>
        </div>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            messages: [],
            template: {
                id: 0,
                text: '',
                error: false,
            },
            counter: 1,
        }
    },
    methods: {

    },
    watch: {
        message: {
            immediate: true,
            handler: function(value) {
                var template = JSON.parse(JSON.stringify(this.template));
                template.id = this.counter++;
                template.text = value.text;
                template.error = value.error;
                this.messages.push(template);
                setTimeout(() => {
                    for (var i = 0; i < this.messages.length; i++) {
                        if (this.messages[i].id == template.id) {
                            this.messages[i].text = '';
                        }
                    }
                }, 4000);
            },
            deep: true,
        }
    },
    props: {
        message: { type: [Array, Object], default: [] },
        error: { type: Boolean, default: false },
    }
}
</script>