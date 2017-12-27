<template>
    <form class="form form-login" v-on:submit.prevent="login">
        <h2 class="form-title">Connexion</h2>
        <div class="container-login">
            <div v-if="message" class="alert" v-bind:class="{ 'alert-danger': erreur}">
                <strong>Erreur !</strong> {{ message }}
            </div>

            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" v-model.trim="email"  type="email" id="email" required>
                <label class="mdl-textfield__label" for="email">Adresse email</label>
            </div>

            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" v-model.trim="password"  type="password" id="password" required>
                <label class="mdl-textfield__label" for="password">Mot de passe</label>
            </div>

            <div class="form-block">
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored submit_login">
                  Connexion
                </button>
                <router-link to="/password-reset">Mot de passe oublié?</router-link>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        name: 'login',
        data () {
            return {
                loader: false,
                erreur: false,
                email: '',
                password: '',
                message: ''
            }
        },
        methods: {
            login () {
                erreur: false
                message: ''
                this.$http.post('/connexion', {
                    email: this.email,
                    password: this.password
                }).then((response) => {
                    if (response.data.succes == true) {
                        this.$router.go('/accueil')
                    } else {
                        this.erreur = true
                        this.password = ''
                    }
                    this.message = response.data.message
                }, () => {
                    this.erreur = true
                    this.password = ''
                })
            }
        }
    }
</script>
