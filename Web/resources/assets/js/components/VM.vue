<template>
    <div>
        <div class="mdl-grid">
            <div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid filtres">
                <h5>Filtres</h5>
                <div class="actions">
                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="vm_date">
                      <input type="checkbox" id="vm_date" class="mdl-checkbox__input">
                      <span class="mdl-checkbox__label">Trier par date</span>
                    </label>
                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="vm_favoris">
                      <input type="checkbox" id="vm_favories" class="mdl-checkbox__input">
                      <span class="mdl-checkbox__label">Mes favoris</span>
                    </label>
                </div>
            </div>
            <div class="vm-list mdl-grid">
                <div class="vm mdl-shadow--2dp mdl-cell mdl-cell--4-col ajouter_vm" id="show-modal-example">
                    <div class="symbole">
                        <i class="material-icons">add</i>
                    </div>
                    <div class="texte">
                        <span>Créer une VM</span>
                    </div>
                </div>
                <div class="vm mdl-shadow--2dp mdl-cell mdl-cell--4-col">
                    <div class="statut on">
                        En service
                    </div>
                    <div class="img-vm">
                        <img src="https://image.flaticon.com/icons/svg/148/148820.svg" alt="">
                    </div>
                    <h6>"VM Développement"</h6>
                    <div class="contenu">
                        <div class="infos">
                            <div class="description">
                                <span class="vm-titre"><i class="material-icons">computer</i> Ubuntu 16.04 64bits</span>
                                <hr>
                                <p><i class="material-icons">list</i> Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                    Proin libero purus, tempus eu venenatis eu, ullamcorper in elit. Nulla auctor nisl eu diam lacinia rutrum.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="menu">
                        <a href="#" class="active">Infos</a>
                        <a href="#" class="details-btn">Détails</a>
                        <a href="#" id="options_vm">Options</a>
                        <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect"
                            for="options_vm">
                          <li class="mdl-menu__item mdl-menu__item--full-bleed-divider">Supprimer</li>
                          <li disabled class="mdl-menu__item">Allumer</li>
                          <li class="mdl-menu__item">Eteindre</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <creation-vm></creation-vm>
    </div>
</template>

<script>
    export default {
        mounted() {
            'use strict';
            var dialog = document.querySelector('#modal-example');
            var closeButton = dialog.querySelector('button');
            var showButton = document.querySelector('#show-modal-example');
            if (! dialog.showModal) {
                dialogPolyfill.registerDialog(dialog);
            }
            var closeClickHandler = function(event) {
                dialog.close();
            };
            var showClickHandler = function(event) {
                dialog.showModal();
            };
            showButton.addEventListener('click', showClickHandler);
            closeButton.addEventListener('click', closeClickHandler);
        },
        //Fixe le problème du select non actualisé
        created () {
          this.$nextTick(() => {
             componentHandler.upgradeDom();
             getmdlSelect.init(".getmdl-select")
          });
        }
    }
</script>
