<template>
    <div>
        <div class="mdl-grid">
            <h3 class="titre">Espace administrateur</h3>
        </div>
        <div class="mdl-grid">
            <div class="mdl-shadow--2dp mdl-cell mdl-cell--4-col">
                <div class="count vm">
                    <span class="compteur"><span class="chiffre">6</span> </span>
                    <span class="titre">Utilisateurs</span>
                </div>
            </div>
            <div class="mdl-shadow--2dp mdl-cell mdl-cell--4-col">
                <div class="count conteneur">
                    <span class="compteur"><span class="chiffre">3</span> </span>
                    <span class="titre">VM</span>
                </div>
            </div>
            <div class="mdl-shadow--2dp mdl-cell mdl-cell--4-col">
                <div class="count status on">
                    <span class="compteur"><span class="chiffre">1</span> </span>
                    <span class="titre">Conteneurs</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {

        }
    }
</script>
