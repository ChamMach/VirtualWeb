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
            <div class="mdl-cell--12-col mdl-grid">
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect btn_creer" id="show-modal-example">
                    <i class="material-icons">add</i> Créer une VM
                </button>
            </div>
            <div class="vm-list mdl-grid">
                <div class="vm mdl-shadow--2dp mdl-cell mdl-cell--4-col">
                    <div class="img-vm">
                        <img src="https://image.flaticon.com/icons/svg/148/148820.svg" alt="">
                    </div>
                    <h6>"VM Développement"</h6>
                    <div class="contenu">
                        <ul class="menu">
                            <li class="active"><a href="#"><i class="material-icons">info_outline</i></a></li>
                            <li><a href="#"><i class="material-icons">list</i></a></li>
                            <li class="options">
                                <button id="options_vm"
                                        class="mdl-button mdl-js-button mdl-button--icon">
                                  <i class="material-icons">more_vert</i>
                                </button>

                                <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect"
                                    for="options_vm">
                                  <li class="mdl-menu__item mdl-menu__item--full-bleed-divider">Supprimer</li>
                                  <li disabled class="mdl-menu__item">Allumer</li>
                                  <li class="mdl-menu__item">Eteindre</li>
                                </ul>
                            </li>
                        </ul>
                        <div class="infos">
                            <div class="description">
                                <span class="vm-titre"><b>Système :</b> Ubuntu 16.04 64bits</span>
                                <p><b>Description :</b> Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                    Proin libero purus, tempus eu venenatis eu, ullamcorper in elit. Nulla auctor nisl eu diam lacinia rutrum.
                                </p>
                            </div>
                        </div>
                        <div class="status online">
                            En service
                        </div>
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
        }
    }
</script>
