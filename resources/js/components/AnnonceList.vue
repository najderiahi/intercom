<template>
    <div ref="annoncesWrapper">
        <template v-if="annonces.length > 0">
            <annonce-element :annonce="annonce" :user="user" v-for="annonce in annonces" :key="annonce.id"></annonce-element>
            <div class="text-center my-3">
                <button class="btn btn-primary" @click="this.loadMoreAnnonces" v-show="this.nextUrl && !this.loading">
                    Charger
                    plus
                </button>
            </div>
        </template>
        <div v-else>
            <div class="card card-body d-flex flex-column align-items-center justify-content-center border-0 shadow-sm">
                <img src="/not_found.svg" alt="" class="w-75 text-center img-responsive">
                <p class="text-muted text-center my-3">Pas d'annonces disponibles pour l'instant</p>
            </div>
        </div>
    </div>
</template>

<script>
    import AnnonceElement from './AnnonceElement'

    export default {
        props: {
            initialUrl: String,
            user: Number
        },
        data() {
            return {
                annonces: [],
                nextUrl: null,
                loading: true,
            }
        },
        methods: {
            async loadAnnonces(url) {
                let response = await fetch(url, {
                    credentials: 'same-origin',
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    this.annonces = [...this.annonces, ...data['data']];
                    this.nextUrl = data['next_page_url'];
                } else {
                    this.loading = false
                }
            },
            handleScroll(event) {
                let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight;
                if (bottomOfWindow) {
                    this.loading = true
                    this.loadAnnonces(this.nextUrl);
                }
            },
            async loadMoreAnnonces() {
                await this.loadAnnonces(this.nextUrl);
            }

        },
        async mounted() {
            await this.loadAnnonces(this.initialUrl);
        },
        created() {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed() {
            window.removeEventListener('scroll', this.handleScroll);
        },
        components: {AnnonceElement}
    }
</script>
