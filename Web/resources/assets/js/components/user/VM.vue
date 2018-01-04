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
                      <input type="checkbox" id="vm_favoris" class="mdl-checkbox__input">
                      <span class="mdl-checkbox__label">Mes favoris</span>
                    </label>
                </div>
            </div>
            <div class="vm-list mdl-grid mdl-cell--12-col">
                <div class="vm mdl-shadow--2dp mdl-cell mdl-cell--4-col no_data" v-if="vm == null">
                    <div class="icon">
                        <i class="material-icons">error_outline</i>
                    </div>
                    <div class="texte">
                        <span>Le serveur n'est pas disponible pour le moment</span><br>
                        <small>Nous faisons tout notre possible pour corriger ce problème</small>
                    </div>
                </div>
                <div class="vm mdl-shadow--2dp mdl-cell mdl-cell--4-col ajouter_vm" id="show-modal-example" v-else>
                    <div class="symbole">
                        <i class="material-icons">add</i>
                    </div>
                    <div class="texte">
                        <span>Créer une VM</span>
                    </div>
                </div>
                <div class="vm mdl-shadow--2dp mdl-cell mdl-cell--4-col" v-for="(value, key, index) in vm" v-bind:class="'vm_'+key" :data-key="'vm_'+key">
                    <div class="statut" v-bind:class="value.statut">
                        <template v-if="value.statut === 'on'">
                            En service
                        </template>
                        <template v-else-if="value.statut === 'inconnu'">
                            Inconnu
                        </template>
                        <template v-else>
                            Éteint
                        </template>
                    </div>
                    <div class="img-vm">
                        <img src="https://image.flaticon.com/icons/svg/148/148820.svg" alt="">
                    </div>
                    <h6>"{{ value.nom }}"</h6>
                    <div class="infos_bloc current bloc_interactif">
                        <div class="contenu">
                            <div class="infos">
                                <div class="description">
                                    <span class="vm-titre"><i class="material-icons">computer</i> {{ value.os }}</span>
                                    <hr>
                                    <p><i class="material-icons">list</i> {{ value.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="details_bloc bloc_interactif">
                        <ul>
                            <li><b>OS : </b>{{ value.os }}</li>
                            <li><b>CPU : </b>{{ value.cpu }}</li>
                            <li><b>RAM : </b>{{ value.ram }} ({{ value.id_unite_ram }})</li>
                            <li><b>Stockage logique : </b>{{ value.sto_l }} ({{ value.id_unite_sto_l }})</li>
                            <li><b>Stockage réel : </b>{{ value.sto_r }} ({{ value.id_unite_sto_r }})</li>
                        </ul>
                    </div>
                    <div class="options_bloc bloc_interactif">
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect supprimer" data-action="supprimer">
                            <i class="material-icons">delete</i> Supprimer
                        </button>
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect allumer" data-action="allumer">
                            <i class="material-icons">play_arrow</i> Allumer
                        </button>
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect eteindre" data-action="eteindre">
                            <i class="material-icons">power_settings_new</i> Éteindre
                        </button>
                    </div>
                    <div class="menu">
                        <a href="#" class="infos_btn active" @click="showHide" data-action="infos_bloc">Infos</a>
                        <a href="#" class="details_btn" @click="showHide" data-action="details_bloc">Détails</a>
                        <a href="#" class="options_btn" @click="showHide" data-action="options_bloc">Options</a>
                    </div>
                </div>
            </div>
        </div>
        <creation-vm></creation-vm>
    </div>
</template>

<script>
    var vmTmp
    //On regarde s'il y a des VM
    if (dataArray.vm == null) {
        vmTmp = null
    } else {
        vmTmp = dataArray.vm
    }
    export default {
        data: function () {
            return {
                vm: vmTmp,
                isActive: false,
            }
        },
        mounted() {
            'use strict';
            //S'il n'y a pas de VM, pas besoin de modal
            if (vmTmp !== null) {
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
        },
        //Fixe le problème du select non actualisé
        created () {
          this.$nextTick(() => {
             componentHandler.upgradeDom();
             getmdlSelect.init(".getmdl-select")
          });
      },
      methods: {
          showHide(event)
          {
              var key = event.target.parentElement.parentElement.attributes["0"].value
              var elementVm = $('.'+key)
              var action = event.target.dataset.action
              elementVm.find('.active').removeClass('active')
              console.log(elementVm);
              elementVm.find('.current').removeClass('current')
              elementVm.find('.'+action).addClass('current')
              event.target.classList.add('active')
          }
      }
    }
</script>
