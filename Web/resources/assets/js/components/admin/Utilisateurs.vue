<template>
    <div>
        <div class="mdl-grid">
            <div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid tableau">
                <table class="mdl-data-table mdl-js-data-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="value in users" :data-id='value.id'>
                            <td>{{ value.nom }}</td>
                            <td>{{ value.prenom }}</td>
                            <td>{{ value.email }}</td>
                            <td>
                                <span class="small-btn consulter">Consulter</span>
                                <span class="small-btn supprimer">Supprimer</span>
                                <span class="small-btn modifier">Modifier</span>
                            </td>
                        </tr>
                  </tbody>
                </table>
            </div>
        </div>
        <div class="mdl-grid">
            <div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-grid card">
                <h3 class="mdl-cell--12-col header">Créer un utilisateur</h3>
                <div class="mdl-cell--12-col contenu">
                    <form class="form form-creation-user" v-on:submit.prevent="addItem">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="nom" v-model="user.nom" required>
                            <label class="mdl-textfield__label" for="nom">Nom</label>
                        </div>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="prenom" v-model="user.prenom" required>
                            <label class="mdl-textfield__label" for="prenom">Prénom</label>
                        </div>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="email" id="email" v-model="user.email" required>
                            <label class="mdl-textfield__label" for="email">Email</label>
                        </div>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="password" pattern=".{6,}" v-model="user.password" required>
                            <label class="mdl-textfield__label" for="password">Mot de passe</label>
                        </div>
                        <div class="mdl-cell--12-col" id="radio">
                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="utilisateur">
                              <input type="radio" id="utilisateur" class="mdl-radio__button" name="options" checked v-model="user.status" value="0">
                              <span class="mdl-radio__label">Utilisateur</span>
                            </label>
                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="administrateur">
                              <input type="radio" id="administrateur" class="mdl-radio__button" name="options" v-model="user.status" value="1">
                              <span class="mdl-radio__label">Administrateur</span>
                            </label>
                        </div>
                        <div class="mdl-dialog__actions">
                            <button class="mdl-button submit_create">Créer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'utilisateurs',
        data: function () {
            return {
                users: [],
                user: {},
            }
        },
        created() {
            this.getUsers()
        },
        methods: {
            addItem(){
                this.$http.post('/create_user', {
                    nom: this.user.nom,
                    prenom: this.user.prenom,
                    email: this.user.email,
                    password: this.user.password,
                    status: this.user.status,
                }).then((response) => {
                    if (response.erreur == false) {
                        this.getUsers()
                    }
                }, () => {
                    console.log('erreur');
                }) 
            },
            getUsers(){
                this.$http.post('/get_users', {
                }).then((response) => {
                    if (response.data.error == false) {
                        this.users = response.data.users
                    }
                }, () => {
                    console.log('erreur');
                }) 
            }
        }
    }
</script>
