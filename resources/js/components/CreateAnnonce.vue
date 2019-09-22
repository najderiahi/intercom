<template>
    <div class="card border-0 shadow-sm my-2">
        <div class="card-body">
            <form @submit.prevent="submit">
                <div class="form-group">
                                <textarea class="form-control border-0 shadow-none" v-model="content" placeholder="Entrez votre annonce"
                                          rows="2"></textarea>
                    <div class="text-right my-2">
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                content: ""
            }
        },
        methods: {
            async submit() {
                if(!content) return;

                const data = {content: this.content};
                let response = await fetch("/api/annonces", {
                    credentials: 'same-origin',
                    method: "POST",
                    headers: {
                        "X-Requested-With" : "XMLHttpRequest",
                        "X-CSRF-TOKEN" : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if(response.ok) {
                    console.log("Done !")
                } else {
                    // TODO: Something in JS
                }
            }
        }
    }
</script>
