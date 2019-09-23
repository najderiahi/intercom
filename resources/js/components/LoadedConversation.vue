<template>
    <div class="card">
        <div class="card-header bg-white text-center">{{ name }}</div>
        <div class="card-body position-relative">
            <div class="wrapper conversation-body">
                <Message :message="message" :key="message.id" :user="user" v-for="message in messages"></Message>
            </div>
            <form @submit.prevent="sendMessage">
                <hr>
                <div class="form-group">
                                <textarea class="form-control border-0 shadow-none" v-model="content"
                                          placeholder="Entrez votre annonce" @keypress.enter="sendMessage"
                                          rows="2"></textarea>
                    <div class="text-right my-2">
                        <button type="submit" class="btn btn-primary" @click.prevent="sendMessage">Envoyer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import Message from './Message'
    import {mapGetters} from 'vuex'

    export default {
        computed: {
            ...mapGetters(['user']),
            messages() {
                return this.$store.getters.messages(this.$route.params.id);
            },
            count() {
                return this.$store.getters.conversation(this.$route.params.id).count;
            },
            name() {
                return this.$store.getters.conversation(this.$route.params.id).first_name + " " + this.$store.getters.conversation(this.$route.params.id).last_name;
            },
            lastMessage() {
                return this.messages[this.messages.length - 1]
            }
        },
        data() {
            return {
                content: ''
            }
        },
        methods: {
            async loadConversationMessages() {
                await this.$store.dispatch("loadConversationMessages", this.$route.params.id)
                if (this.messages.length < this.count) {
                    this.$messages.addEventListener("scroll", this.onScroll);
                }
            },
            async sendMessage(e) {
                try {

                    if (e.shiftKey === false) {
                        await this.$store.dispatch("sendMessage", {
                            content: this.content,
                            userId: this.$route.params.id
                        })
                        this.content = ""
                    }
                } catch (e) {
                }
            },
            async onScroll() {
                if (this.$messages.scrollTop === 0) {
                    const previousHeight = this.$messages.scrollHeight
                    await this.$store.dispatch('loadPreviousMessages', {conversationId: this.$route.params.id})
                    this.$nextTick(() => {
                        this.$messages.scrollTop = this.$messages.scrollHeight - previousHeight
                    })
                    this.$messages.removeEventListener("scroll", this.onScroll);
                    if (this.messages.length < this.count) {
                        this.$messages.addEventListener("scroll", this.onScroll);
                    }
                }
            },
            onVisible() {
                if (document.hidden === false) {
                    this.$store.dispatch("loadConversationMessages", this.$route.params.id)
                }
            },
            scrollBot() {
                this.$nextTick(() => {
                    this.$messages.scrollTop = this.$messages.scrollHeight;
                })
            }
        },
        watch: {
            '$route.params.id': function () {
                this.loadConversationMessages()
            },
            lastMessage() {
                this.scrollBot()
            }
        },
        mounted() {
            this.loadConversationMessages()
            this.$messages = this.$el.querySelector('.conversation-body')
            document.addEventListener('visibilitychange', this.onVisible);
        },
        destroyed() {
            document.removeEventListener('visibilitychange', this.onVisible);
        },
        components: {Message}
    }
</script>
