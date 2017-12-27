<template>
    <div>
        <div class="user-data">
            <div class="user-picture">
                <img src="https://image.flaticon.com/icons/svg/149/149074.svg" alt="">
            </div>
            <div class="user-name">
                <span>{{ user }}</span>
            </div>
        </div>
        <div class="menu-links">
            <ul>
                <li class="active">Accueil</li>
                <li>Mes machines</li>
                <li>Ajouter</li>
                <li>Supprimer</li>
                <li><a href="deconnexion">Déconnexion</a></li>
            </ul>
        </div>
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