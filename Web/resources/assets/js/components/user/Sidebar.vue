<template>
    <div class="mdl-layout__drawer sidebar mdl-shadow--4dp">
        <div class="user-data">
            <div class="user-picture">
                <img src="/img/user.svg" alt="">
            </div>
            <div class="user-name">
                <span>{{ user }}</span>
            </div>
        </div>
      <nav class="mdl-navigation menu-links fadeInLeft animated">
        <router-link to="accueil" class="mdl-navigation__link"><i class="material-icons">home</i> Accueil</router-link>
        <router-link to="vm" class="mdl-navigation__link"><i class="material-icons">dvr</i> Virtuals Machines</router-link>
        <router-link to="conteneur" class="mdl-navigation__link"><i class="material-icons">local_laundry_service</i> Conteneurs</router-link>
        <a class="mdl-navigation__link" href="deconnexion_user"><i class="material-icons">exit_to_app</i> Déconnexion</a>
      </nav>
    </div>
</template>

<script>
    export default {
        name: 'sidebar',
        data () {
            return {
                user: dataArray.nom
            }
        },
    }
</script>
