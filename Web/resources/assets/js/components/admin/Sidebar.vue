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
      <nav class="mdl-navigation menu-links">
        <router-link to="administration" class="mdl-navigation__link"><i class="material-icons">home</i> Accueil</router-link>
        <router-link to="utilisateurs" class="mdl-navigation__link"><i class="material-icons">dvr</i> Utilisateurs</router-link>
        <a class="mdl-navigation__link" href="deconnexion_admin"><i class="material-icons">exit_to_app</i> Déconnexion</a>
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
