<template>
    <div>
        <div class="mdl-grid">
            <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp mdl-cell mdl-cell--12-col tableau">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="value in users" :data-id='value.id'>
                        <td>{{ value.nom }}</td>
                        <td>{{ value.prenom }}</td>
                        <td>{{ value.email }}</td>
                        <td>
                            <!-- <span v-on:click="deleteUser(value.id)" @click="verif($event)" class="small-btn supprimer show_modal_verif">Supprimer</span> -->
                            <span @click="set(value, $event)" class="small-btn supprimer show_modal_verif">Supprimer</span>
                            <span @click="modifierUser(value, $event)" class="small-btn modifier show-modal">Modifier</span>
                        </td>
                    </tr>
              </tbody>
            </table>
        </div>
        <div class="mdl-grid">
            <div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-grid card">
                <h3 class="mdl-cell--12-col header">Créer un utilisateur</h3>
                <creation-utilisateur></creation-utilisateur>
            </div>
            <div class="mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-grid card" v-if="editUser">
                <h3 class="mdl-cell--12-col header">Modifier un utilisateur</h3>
                <div class="mdl-cell--12-col contenu">
                    <form class="form form-creation-user" v-on:submit.prevent="addUser">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="nomEdit" v-model="userEditData.nom" required>
                            <label class="mdl-textfield__label" for="nomEdit">Nom</label>
                        </div>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="prenomEdit" v-model="userEditData.prenom" required>
                            <label class="mdl-textfield__label" for="prenomEdit">Prénom</label>
                        </div>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="email" id="emailEdit" v-model="userEditData.email" required>
                            <label class="mdl-textfield__label" for="emailEdit">Email</label>
                        </div>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="passwordEdit" pattern=".{6,}" v-model="userEditData.password">
                            <label class="mdl-textfield__label" for="passwordEdit">Mot de passe</label>
                        </div>
                        <div class="mdl-cell--12-col" id="radio">
                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="utilisateurEdit">
                                <input type="radio" id="utilisateurEdit" class="mdl-radio__button" name="options" checked v-model="userEditData.status" value="0">
                                <span class="mdl-radio__label">Utilisateur</span>
                            </label>
                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="administrateurEdit">
                                <input type="radio" id="administrateurEdit" class="mdl-radio__button" name="options" v-model="userEditData.status" value="1">
                                <span class="mdl-radio__label">Administrateur</span>
                            </label>
                        </div>
                        <div class="mdl-dialog__actions">
                            <button class="mdl-button submit_create">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <modal-verification v-bind:message="message" v-bind:method="verification"></modal-verification>
    </div>
</template>

<script>
    export default {
        name: 'utilisateurs',
        data: function () {
            return {
                users: dataArray.users.data,
                message: null,
                methods: {
                    action: null,
                    idUser: null,
                },
                editUser: false,
                userEditData: {},
            }
        },
        mounted() {
            var dialogButton = document.querySelectorAll('.show_modal_verif');
            var dialog = document.querySelector('#dialog_verif');
            if (!dialog.showModal) {
                dialogPolyfill.registerDialog(dialog);
            }
            dialogButton.forEach(function(elem) {
                elem.addEventListener("click", function() {
                    dialog.showModal();
                });
            });
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
          deleteUser(userId) {
              this.$http.post('/delete_user', {
                  id: userId,
              }).then((response) => {
                  if (response.data.erreur == true) {
                      notyf.alert(response.data.message);
                  } else if (response.data.erreur == false) {
                      notyf.confirm(response.data.message);
                  }
              }, () => {
                  console.log('erreur');
              })
          },
          set(user, click) {
              this.methods.idUser = user.id
              this.methods.action = "delete"
              this.message = "Voulez vous vraiment supprimer l'utilisateur " + user.nom + " " + user.prenom
          },
          verification() {
              if (this.methods.action == "delete") {
                  this.deleteUser(this.methods.idUser)
              }
              this.methods.action = null;
              this.methods.idUser = null;
          },
          modifierUser(user, click) {
              this.editUser = true
              this.$nextTick(() => {
                  componentHandler.upgradeDom();
                  getmdlSelect.init(".getmdl-select")
              });
              this.userEditData.id = user.id
              this.userEditData.nom = user.nom
              this.userEditData.prenom = user.prenom
              this.userEditData.email = user.email
          },
          addUser() {
              this.$http.post('/edit_user', {
                  id: this.userEditData.id,
                  nom: this.userEditData.nom,
                  prenom: this.userEditData.prenom,
                  email: this.userEditData.email,
                  password: this.userEditData.password,
                  status: this.userEditData.status,
              }).then((response) => {
                  if (response.data.erreur == true) {
                      notyf.alert(response.data.message);
                  } else if (response.data.erreur == false) {
                      notyf.confirm(response.data.message);
                      this.editUser = false
                      this.userEditData = {}
                  }
              }, () => {
                  console.log('erreur');
              })
          }
      }
    }
</script>
