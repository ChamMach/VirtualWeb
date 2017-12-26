<template>
    <form class="form form-login" :submit.prevent="login">
        <h2 class="form-title">Connexion</h2>

        <div class="info info--error" v-if="infoError">Login failed. Please try again.</div>

        <div class="container-login" :class="{'is-waiting': loader}">
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
                infoError: false,
                email: '',
                password: ''
            }
        },
        methods: {
            login () {
                this.loader = true
                this.infoError = false
                this.$http.post('/login', {
                    email: this.email,
                    password: this.password
                }).then((response) => {
                    console.log("succes");
                    //localStorage.setItem('token', response.body.token)
                    //store.commit('LOGIN_USER')
                    //router.push('/')
                }, () => {
                    console.log("erreur");
                    this.infoError = true
                    this.loader = false
                    this.password = ''
                })
            }
        }
    }
</script>