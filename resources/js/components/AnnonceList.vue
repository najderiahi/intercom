<template>
    <div>
        <annonce-element :annonce="annonce" v-for="annonce in annonces" :key="annonce.id"></annonce-element>
    </div>
</template>

<script>
    import AnnonceElement from './AnnonceElement'

    export default {
        data () {
            return {
                annonces: []
            }
        },
        async mounted() {
            let response = await fetch("/api/annonces", {
                credentials: 'same-origin',
                headers: {
                    "X-Requested-With" : "XMLHttpRequest",
                    "X-CSRF-TOKEN" : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            if(response.ok) {
                this.annonces = await response.json();
                console.log(this.annonces);
            }
        },
        components: {AnnonceElement}
    }
</script>
