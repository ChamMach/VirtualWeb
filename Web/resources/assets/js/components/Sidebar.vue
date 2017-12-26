<template>
    <nav>
        <div class="user-data">
            <div class="user-picture">
                <img src="https://image.flaticon.com/icons/svg/149/149074.svg" alt="">
            </div>
            <div class="user-name">
                <span>Jean Eude</span>
            </div>
        </div>
        <div class="menu-links">
            <ul>
                <li>Accueil</li>
                <li>Mes machines</li>
                <li>Ajouter</li>
                <li>Supprimer</li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
