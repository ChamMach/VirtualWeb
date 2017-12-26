<template>
    <form class="form form-login" v-on:submit.prevent="login">
        <h2 class="form-title">Connexion</h2>
        <div class="container-login">
            <div v-if="message" class="alert" v-bind:class="{ 'alert-success': succes, 'alert-danger': erreur}">
                <strong v-if="erreur">Erreur !</strong><strong v-else>Succès !</strong> {{ message }}
            </div>
            <div class="input-field">
                <input v-model.trim="email"  id="email" type="email" required>
                <label for="email" class="">Adresse email</label>
            </div>
            <div class="input-field">
                <input v-model.trim="password"  id="password" type="password" required>
                <label for="email" class="">Mot de passe</label>
            </div>

            <div class="form-block form__actions">
                <router-link to="/password-reset">Mot de passe oublié?</router-link>
                <button class="submit_login">Connexion</button>
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
                succes: false,
                email: '',
                password: '',
                message: ''
            }
        },
        methods: {
            login () {
                erreur: false
                succes: false
                message: ''
                this.$http.post('/connexion', {
                    email: this.email,
                    password: this.password
                }).then((response) => {
                    if (response.data.succes == true) {
                        this.succes = true
                    } else {
                        this.erreur = true
                        this.password = ''
                    }
                    this.message = response.data.message
                    //localStorage.setItem('token', response.body.token)
                    //store.commit('LOGIN_USER')
                    //router.push('/')
                }, () => {
                    this.infoError = true
                    this.loader = false
                    this.password = ''
                })
            }
        }
    }
</script>
