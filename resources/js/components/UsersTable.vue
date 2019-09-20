<template>
    <table class="table">
        <tr>
            <th :class="[this.usersSortingState !== '' ? 'text-primary' : '' ]">
                <span @click="this.toogleUserSortingOrder">Utilisateurs</span>
                <span
                v-show="this.usersSortingState === 'ASC'"><i class="fas fa-long-arrow-alt-up"></i></span>
                <span
                v-show="this.usersSortingState === 'DESC'"><i class="fas fa-long-arrow-alt-down"></i></span>
            </th>
            <th :class="[this.activitySortingState !== '' ? 'text-primary' : '' ]">
                <span @click="this.toogleActivitySortingOrder">Activité</span>
                <span
                    v-show="this.activitySortingState === 'ASC'"><i class="fas fa-long-arrow-alt-up"></i></span>
                <span
                    v-show="this.activitySortingState === 'DESC'"><i class="fas fa-long-arrow-alt-down"></i></span></th>
            <th>Actions</th>
        </tr>
        <tr v-for="user in users">
            <td>
                <div class="d-flex">
                    <img :src="user.avatar_url" alt="" class="avatar-img" v-if="user.avatar_url !== null">
                    <div
                        class="avatar-img bg-light-success d-flex justify-content-center align-items-center text-white large-text"
                        v-else>
                        {{ `${user.first_name[0]}${user.last_name[0]}` }}
                    </div>
                    <div class="d-flex flex-column">
                        <span>{{ `${user.first_name} ${user.last_name}` }}</span>
                        <span class="small text-muted">{{ user.email }}</span>
                    </div>
                </div>
            </td>
            <td>
                <span class="badge badge-success" v-if="user.active">Actif</span>
                <span class="badge badge-danger" v-else>Inactif</span>
            </td>
            <td>
                <div class="dropdown">
                    <span class="text-primary" id="menuButton" data-toggle="dropdown" aria-haspopup="true"
                          aria-expanded="false"><i class="fas fa-ellipsis-h"></i></span>
                    <div class="dropdown-menu border-0 shadow-sm" aria-labelledby="menuButton">
                        <a class="dropdown-item" href="#"><i class="fas fa-eye"></i> Consulter le profil</a>
                        <a class="dropdown-item text-primary" @click.prevent="activate(user)" href="#"
                           v-if="!user.active"><i class="fas fa-lock-open"></i> Activer l'utilisateur</a>
                        <a class="dropdown-item text-primary" @click.prevent="deactivate(user)" href="#" v-else><i
                            class="fas fa-lock"></i> Désactiver l'utilisateur</a>
                        <a class="dropdown-item text-danger" @click.prevent="deleteUser(user)" href="#"><i
                            class="fas fa-trash-alt"></i> Supprimer l'utilisateur</a>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</template>
<script>
    export default {
        props: {currentUser: Number},
        data() {
            return {
                users: [],
                sorting: {user: "", activity: ""},
            }
        },
        async mounted() {
            let response = await fetch('/api/users', {
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content'),
                }
            });

            if (response.ok) {
                const data = await response.json();

                const index = data.findIndex(u => u.id === parseInt(this.currentUser));
                if (~index)
                    data.splice(index, 1);

                this.users = data
            } else {
                //TODO: Something in JS
            }
        },
        methods: {
            async deleteUser(user) {
                let response = await fetch(`api/users/${user.id}`, {
                    credentials: 'same-origin',
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                },);
                if (response.ok) {
                    const index = this.users.findIndex(u => u.id === user.id);
                    if (~index)
                        this.users.splice(index, 1);
                }

            },

            async activate(user) {

                const data = {active: 1};

                let response = await fetch(`api/users/${user.id}/activation`, {
                    credentials: 'same-origin',
                    method: 'PATCH',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                },);

                if (response.ok) {
                    user.active = true
                } else {
                    // TODO: Something in JS
                }
            },

            async deactivate(user) {
                const data = {active: 0};

                let response = await fetch(`api/users/${user.id}/activation`, {
                    credentials: 'same-origin',
                    method: 'PATCH',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                },);

                if (response.ok) {
                    user.active = false
                } else {
                    // TODO: Something in JS
                }
            },

            toogleUserSortingOrder() {
                if (this.sorting.user === "") {
                    this.sorting.user = "ASC"
                } else if (this.sorting.user === "ASC") {
                    this.sorting.user = "DESC"
                } else {
                    this.sorting.user = ""
                }
            },
            toogleActivitySortingOrder() {
                if (this.sorting.activity === "") {
                    this.sorting.activity = "ASC"
                } else if (this.sorting.activity === "ASC") {
                    this.sorting.activity = "DESC"
                } else {
                    this.sorting.activity = ""
                }
            },

            goToNextPage() {

            },

            goToPreviousPage() {

            },

            goTopageFromAnIndex(index) {

            }
        },
        computed: {
            activitySortingState() {
                return this.sorting.activity;
            },
            usersSortingState() {
                return this.sorting.user;
            }
        }
    }
</script>
<style>
    table {
        background-color: white;
        border-radius: 4px;
        border-collapse: collapse;
        box-shadow: 0px 0px 13px 0px rgba(0, 0, 0, 0.1);
    }

    .table td, th {
        font-weight: normal;
        border: none !important;
    }

    .table th {
        cursor: pointer;
    }

    tr {
        border-bottom: 1px solid #f0f3ff;
    }

    .avatar-img {
        width: 60px;
        height: 60px;
        border-radius: 4px;
        box-shadow: 0px 0px 13px 0px rgba(0, 0, 0, 0.1);
        margin: 0 .5rem;
    }

    .large-text {
        font-size: 1.75rem;
    }
</style>
