<template>
    <div>
        <h2 class="header">Accueil</h2>
        <div class="vm-list">
            <div class="vm col s4">
                <div class="img-vm">
                    <img src="https://image.flaticon.com/icons/svg/148/148820.svg" alt="">
                </div>
                <div class="contenu">
                    <ul class="menu">
                        <li class="active"><a href="#"><i class="material-icons">info_outline</i></a></li>
                        <li><a href="#"><i class="material-icons">list</i></a></li>
                        <li><a href="#"><i class="material-icons right">more_vert</i></a></li>
                    </ul>
                    <div class="infos">
                        <div class="description">
                            <span class="vm-titre"><b>Système :</b> Windows 10 64bits</span>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                Proin libero purus, tempus eu venenatis eu, ullamcorper in elit. Nulla auctor nisl eu diam lacinia rutrum.
                            </p>
                        </div>
                        <div class="status online">
                            En service
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
