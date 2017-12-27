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
        <router-link to="accueil" class="mdl-navigation__link">Accueil</router-link>
        <a class="mdl-navigation__link" href="">Mes machines</a>
        <router-link to="ajouter" class="mdl-navigation__link">Ajouter</router-link>
        <router-link to="supprimer" class="mdl-navigation__link">Supprimer</router-link>
        <a class="mdl-navigation__link" href="deconnexion">Déconnexion</a>
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
