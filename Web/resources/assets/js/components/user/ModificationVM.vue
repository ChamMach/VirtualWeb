<template>
    <dialog class="mdl-dialog modal" id="modal_edit">
        <div class="mdl-dialog__content">
            <h3>Modification d'une machine virtuelle</h3>
            <form v-on:submit.prevent="editVM">
                <div class="mdl-grid">
                    <div class="mdl-cell--12-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input input_form text-zone" type="text" id="nom" pattern="[^_()/><\][\\\x22,;|]+" v-model="vmEdit.nom">
                            <label class="mdl-textfield__label" for="nom">Nom de la VM</label>
                        </div>
                    </div>
                    <div class="slider">
                        <div class="ram_input">
                            <label class="">Ram ({{ ramMo }} Mo)</label>
                            <input class="mdl-slider mdl-js-slider input_form" type="range" :min="minRam" max="5000" value="200" tabindex="0" v-model="ramMo">
                        </div>
                        <div class="cpu_input mt16">
                            <label class="">CPU ({{ vmEdit.cpu }})</label>
                            <input class="mdl-slider mdl-js-slider input_form" type="range" min="1" step="1" max="4" value="1" tabindex="0" v-model="vmEdit.cpu">
                        </div>
                        <div class="stockage_input mt16">
                            <label class="">Stockage ({{ stockageMo }})</label>
                            <input class="mdl-slider mdl-js-slider input_form" type="range" :min="minStockage" step="1" max="60000" value="100" tabindex="0" v-model="stockageMo">
                        </div>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-cell--12-col">
                      <textarea class="mdl-textfield__input input_form text-zone" minlength="5" type="text" rows= "3" id="description" v-model="vmEdit.description"></textarea>
                      <label class="mdl-textfield__label" for="description">Description</label>
                    </div>
                </div>
                <div class="mdl-dialog__actions">
                    <button type="button" class="mdl-button close_modal" data-modal="edit">Fermer</button>
                    <button class="mdl-button submit_create">Modifier</button>
                </div>
            </form>

        </div>
    </dialog>
</template>

<script>
    export default {
        props: ["vm", 'refresh'],
        data () {
            return {
                vmEdit: Object.assign({}, this.vm),
                minStockage: 100,
                ramMo: 100,
                stockageMo: 100,
                minRam: 100,
                originalNom: '',
            }
        },
        watch: {
            vm(newVm) {
                this.vmEdit = Object.assign({}, newVm)
                this.vmEdit.sto_l = Math.ceil(this.vmEdit.sto_l)
                this.minStockage = this.vmEdit.stocakge_mo
                this.minRam = this.vmEdit.ram_mo
                this.ramMo = this.vmEdit.ram_mo
                this.stockageMo = this.vmEdit.stocakge_mo
                this.originalNom = this.vmEdit.nom
            }
        },
        methods: {
            editVM() {
                var error = false
                $("#modal_edit .input_form").each(function() {
                    //Si on n'a pas de valeur dans l'input
                    if (!$(this).val() || this.vmEdit == "") {
                        $(this).parent().addClass('is-invalid')
                        error = true
                    }
                });
                //Si le formulaire est bien remplit
                if (error == false) {
                    this.$http.post('/edit_vm', {
                        nom: this.vmEdit.nom,
                        nomOriginal: this.originalNom,
                        ram: this.ramMo,
                        cpu: this.vmEdit.cpu,
                        stockage: this.stockageMo,
                        description: this.vmEdit.description,
                    }).then((response) => {
                        if (response.data.erreur == false) {
                            notyf.confirm(response.data.message);
                            //On met à jour la liste des VM
                            this.refresh()
                            //On ferme la modal
                            document.querySelector('#modal_edit').close()
                            //Reset les données du formulaire à l'original
                        } else if (response.data.erreur == true) {
                            notyf.alert(response.data.message);
                        }
                    }, () => {
                        console.log('erreur');
                    })
                }
            }
        }
    }
</script>
