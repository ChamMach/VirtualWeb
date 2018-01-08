<template>
    <div class="mdl-layout__drawer sidebar">
        <div class="user-data">
            <div class="user-picture">
                <img src="https://image.flaticon.com/icons/svg/149/149074.svg" alt="">
            </div>
            <div class="user-name">
                <span>{{ user }}</span>
            </div>
        </div>
      <nav class="mdl-navigation menu-links">
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
