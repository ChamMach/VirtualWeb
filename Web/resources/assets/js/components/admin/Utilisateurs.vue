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
                            <span v-on:click="deleteUser(value.id)" class="small-btn supprimer">Supprimer</span>
                            <span class="small-btn modifier show-modal">Modifier</span>
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
        </div>
    </div>
</template>

<script>
    export default {
        name: 'utilisateurs',
        data: function () {
            return {
                users: dataArray.users.data,
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
          deleteUser: function(userId) {
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
          verification()
          {

          }
      }
    }
</script>
