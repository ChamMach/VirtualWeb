<template>
    <div class="mdl-cell--12-col contenu">
        <form class="form form-creation-user" v-on:submit.prevent="addUser">
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
</template>

<script>
    export default {
        data: function () {
            return {
                user: {},
            }
        },
        methods: {
            addUser() {
                this.$http.post('/create_user', {
                    nom: this.user.nom,
                    prenom: this.user.prenom,
                    email: this.user.email,
                    password: this.user.password,
                    status: this.user.status,
                }).then((response) => {

                    if (response.data.erreur == true) {
                        notyf.alert(response.data.message);
                    } else if (response.data.erreur == false) {
                        notyf.confirm(response.data.message);
                    }
                }, () => {
                    console.log('erreur');
                })
            }
        },
    }
</script>
