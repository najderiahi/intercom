<template>
    <div class="row">
        <div class="col-md-3 my-1">
            <div class="list-group">
                <template v-for="conversation in conversations">
                    <div class="list-group-item d-flex flex-column">
                        <img :src="conversation.avatar_url" alt="" v-if="conversation.avatar_url">
                        <router-link :to="{name: 'conversation', params: {id: conversation.id} }" class=" d-flex justify-content-between align-items-center w-100">
                            {{ `${conversation.first_name} ${conversation.last_name}` }}
                            <span class="p-1 bg-primary rounded-circle" v-if="conversation.unread"></span>
                        </router-link>
                    </div>
                </template>
            </div>
        </div>
        <div class="col-md-8 my-1">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        props: {
            user: Number
        },
        computed: {
            ...mapGetters(['conversations'])
        },
        mounted() {
            this.$store.dispatch("loadConversations")
            this.$store.dispatch("setUser", this.user)
        }
    }
</script>
