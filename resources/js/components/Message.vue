<template>
    <div class="text-right mx-3 d-flex" :class="{'justify-content-end' : this.message.sender_id === this.user}">
        <div class="my-2 rounded px-3 message-component py-2 d-table" :class="cls">
            <span class="">{{message.content}}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            message: Object,
            user: Number
        },
        computed: {
            isMe() {
                return this.user === this.$route.params.id
            },
            cls() {
                let cls = []
                if (this.message.sender_id === this.user) {
                    cls.push('bg-primary')
                    cls.push('text-white')
                    cls.push('text-left')
                } else {
                    cls.push('bg-light-primary')
                }
                return cls;
            }
        }
    }
</script>
