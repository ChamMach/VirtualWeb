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
                <div class="vm mdl-shadow--2dp mdl-cell mdl-cell--4-col ajouter_vm" id="show_modal_creation" v-else>
                    <div class="symbole">
                        <i class="material-icons">add</i>
                    </div>
                    <div class="texte">
                        <span>Créer une VM</span>
                    </div>
                </div>
                <div class="vm mdl-shadow--2dp mdl-cell mdl-cell--4-col" v-for="(value, key, index) in vm" v-bind:class="'vm_'+value.id_vm" :data-key="'vm_'+value.id_vm">
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
                        <img src="/img/server.svg" alt="">
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
                            <li><b>RAM : </b>{{ value.ram }} ({{ value.unite_ram }})</li>
                            <li><b>Stockage logique : </b>{{ value.sto_l }} ({{ value.unite_sto_l }})</li>
                            <li><b>Stockage réel : </b>{{ value.sto_r }} ({{ value.unite_sto_r }})</li>
                        </ul>
                    </div>
                    <div class="options_bloc bloc_interactif">
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect supprimer show_modal_verif"
                                data-action="remove" @click="set(value, $event)">
                            <i class="material-icons">delete</i> Supprimer
                        </button>
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect allumer show_modal_verif"
                                data-action="start" @click="set(value, $event)">
                            <i class="material-icons">play_arrow</i> Allumer
                        </button>
                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect eteindre show_modal_verif"
                                data-action="shutdown" @click="set(value, $event)">
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
        <modal-verification v-bind:message="message" v-bind:method="verification"></modal-verification>
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
                message: null,
                methods: {
                    action: null,
                    idUser: null,
                    vm: null,
                    id: null,
                },
            }
        },
        mounted() {
            'use strict';
            //S'il n'y a pas de VM, pas besoin de modal
            if (vmTmp !== null) {
                //Correspond à la modale de création d'une VM
                var dialog_create = document.querySelector('#modal_create');
                var closeButton = dialog_create.querySelector('.close_modal_creation');
                var showButton = document.querySelector('#show_modal_creation');
                if (! dialog_create.showModal) {
                    dialogPolyfill.registerDialog(dialog_create);
                }
                var closeClickHandler = function(event) {
                    dialog_create.close();
                };
                var showClickHandler = function(event) {
                    dialog_create.showModal();
                };
                showButton.addEventListener('click', showClickHandler);
                closeButton.addEventListener('click', closeClickHandler);
            }

            var dialogButton = document.querySelectorAll('.show_modal_verif');
            var dialog = document.querySelector('#dialog_verif');
            if (!dialog.showModal) {
                dialogPolyfill.registerDialog(dialog);
            }
            //Ajout listener sur le bouton au clique pour afficher la modale
            dialogButton.forEach(function(elem) {
                elem.addEventListener("click", function() {
                    dialog.showModal();
                });
            });
            //Pareil mais pour quitter la modale
            dialog.querySelector('.close_modal_verif')
            .addEventListener('click', function() {
                dialog.close();
            });
            dialog.querySelector('.yes_modal_verif')
            .addEventListener('click', function() {
                dialog.close()
            });
        },
        //Fixe le problème du select non actualisé
        created () {
          this.$nextTick(() => {
             componentHandler.upgradeDom();
             getmdlSelect.init(".getmdl-select")
          });
      },
      methods: {
          //Gestion des onglets de la card
          showHide(event)
          {
              var key = event.target.parentElement.parentElement.attributes["0"].value
              var elementVm = $('.'+key)
              var action = event.target.dataset.action
              elementVm.find('.active').removeClass('active')
              elementVm.find('.current').removeClass('current')
              elementVm.find('.'+action).addClass('current')
              event.target.classList.add('active')
          },
          //Initilise les valeurs au clique sur une action
          set(user, click) {
              var texte = click.target.parentElement.parentElement.parentElement.children[2].outerText
              //On récupère l'action
              var action = click.target.parentElement.dataset.action;
              //On affecte ces valeurs aux variables
              this.methods.action = action
              this.methods.idUser = user.id_utilisateur
              this.methods.id = user.id_vm
              //On concatène le nom de la VM pour le script
              this.methods.vm = user.id_utilisateur + '_' + user.nom
              if (action == 'remove') {
                  //Message qui sera visible dans la modale
                  this.message = 'Voulez vous vraiment supprimer la VM ' + texte + ' ?'
              } else if (action == 'start') {
                  this.message = 'Voulez vous vraiment allumer la VM ' + texte + ' ?'
              } else if (action == 'shutdown') {
                  this.message = 'Voulez vous vraiment éteindre la VM ' + texte + ' ?'
              }
              //this.message = message
          },
          getVM() {
              this.$http.post('/get_vm').then((response) => {
                  this.vm = response.data
              }, () => {
                  console.log('erreur')
              })
              var divVM = $('.vm_' + this.methods.id);
              divVM.removeClass('spinner')
              //On réinitialise les valeurs
              this.methods.action = null
              this.methods.idUser = null
              this.methods.vm = null
              this.methods.id = null
          },
          //Méthode appellée lorsque l'utilisateur clique sur le bouton oui dans la modale de vérification
          verification() {
              var divVM = $('.vm_' + this.methods.id);
              divVM.addClass('spinner')
              //On exécute la requête ajax
              this.$http.post('/send_action', {
                  action: this.methods.action,
                  nomVM: this.methods.vm,
                  id: this.methods.idUser,
              }).then((response) => {
                  if (response.data.erreur == false) {
                      notyf.confirm(response.data.message)
                      this.getVM()
                  } else if (response.data.erreur == true) {
                      notyf.alert(response.data.message)
                      divVM.removeClass('spinner')
                      //On réinitialise les valeurs
                      this.methods.action = null
                      this.methods.idUser = null
                      this.methods.vm = null
                      this.methods.id = null
                  }
              }, () => {
                  console.log('erreur')
              })
          },
      }
    }
</script>
