<template>
    <div class="card border-0 shadow-sm my-2">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-top">
                <div class="row">
                    <img :src="annonce.author.avatar_url" alt="" class="avatar-img small-avatar"
                         v-if="annonce.author.avatar">
                    <template v-else>
                        <div
                            class="avatar-img bg-light-success d-flex justify-content-center small-avatar align-items-center text-white large-text">
                            {{ `${annonce.author.first_name[0]}${annonce.author.last_name[0]}` }}
                        </div>
                    </template>
                    <div class="mx-3">
                        <a :href="annonce.author.url" class="text-dark">
                            <h5
                                class="m-0">{{ annonce.author.first_name + " " + annonce.author.last_name }}</h5></a>
                        <span class="text-muted small">{{ formattedDate }}</span>
                    </div>
                </div>
            </div>
            <p class="my-3" v-if="true">{{ annonce.content }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            "annonce": Object,
            "user": Number
        },
        computed: {
            formattedDate() {
                return Date(this.annonce.created_at)
            }
        }
    }
</script>
