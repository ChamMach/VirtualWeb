<template>
    <dialog class="mdl-dialog" id="modal_create">
        <div class="mdl-dialog__content">
            <h3>Création d'une machine virtuelle</h3>
            <form v-on:submit.prevent="createVM">
                <div class="mdl-grid">
                    <div class="mdl-cell--12-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input input_form text-zone" type="text" id="nom" pattern="[^_()/><\][\\\x22,;|]+" v-model="vm.nom">
                            <label class="mdl-textfield__label" for="nom">Nom de la VM</label>
                        </div>
                    </div>
                    <div class="mdl-cell--12-col">
                        <div class="mdl-textfield mdl-js-textfield getmdl-select">
                            <input class="mdl-textfield__input input_form select-systeme" value="" @change="selectChange" readonly>
                            <input value="" type="hidden" id="systeme" name="systeme">
                            <label class="mdl-textfield__label" for="systeme">Système</label>
                            <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu" for="systeme">
                                <li class="mdl-menu__item" data-val="WI7">Windows 7</li>
                                <li class="mdl-menu__item" data-val="CE">Centos</li>
                            </ul>
                        </div>
                    </div>
                    <div class="slider">
                        <div class="ram_input">
                            <label class="">Ram ({{ vm.ram }} Mo)</label>
                            <input class="mdl-slider mdl-js-slider input_form" type="range" min="100" step="100" max="3000" value="200" tabindex="0" v-model="vm.ram">
                        </div>
                        <div class="cpu_input mt16">
                            <label class="">CPU ({{ vm.cpu }})</label>
                            <input class="mdl-slider mdl-js-slider input_form" type="range" min="1" step="1" max="4" value="1" tabindex="0" v-model="vm.cpu">
                        </div>
                        <div class="stockage_input mt16">
                            <label class="">Stockage ({{ vm.stockage }})</label>
                            <input class="mdl-slider mdl-js-slider input_form" type="range" :min="range.min" step="100" max="60000" value="100" tabindex="0" v-model="vm.stockage">
                        </div>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-cell--12-col">
                      <textarea class="mdl-textfield__input input_form text-zone" minlength="5" type="text" rows= "3" id="description" v-model="vm.description"></textarea>
                      <label class="mdl-textfield__label" for="description">Description</label>
                    </div>
                </div>
                <div class="mdl-dialog__actions">
                    <button type="button" class="mdl-button close_modal_creation">Fermer</button>
                    <button class="mdl-button submit_create">Créer</button>
                </div>
            </form>

        </div>
    </dialog>
</template>

<script>
    export default {
        name: 'creation_vm',
        data () {
            return {
                vm: {
                    nom: null,
                    ram: 100,
                    cpu: 1,
                    stockage: 100,
                    systeme: null,
                    description: null,
                },
                range: {
                    min: 100
                }
            }
        },
        methods: {
            onChange (e) {
                this.node.sum = e.target.value;
            },
            selectChange (e) {
                if (e.target.nextElementSibling.attributes["0"].value == "CE") {
                    this.range.min = 8000
                    this.vm.stockage = 8000
                } else if (e.target.nextElementSibling.attributes["0"].value == "WI7") {
                    this.range.min = 25600
                    this.vm.stockage = 25600
                }
            },
            createVM() {
                this.vm.systeme = $('#systeme').val();
                var error = false
                $(".input_form").each(function() {
                    //Si on n'a pas de valeur dans l'input
                    if (!$(this).val() || this.vm == "") {
                        $(this).parent().addClass('is-invalid')
                        error = true
                    }
                });
                //Si le formulaire est bien remplit
                if (error == false) {
                    this.$http.post('/create_vm', {
                        nom: this.vm.nom,
                        systeme: this.vm.systeme,
                        ram: this.vm.ram,
                        cpu: this.vm.cpu,
                        stockage: this.vm.stockage,
                        description: this.vm.description,
                    }).then((response) => {
                        if (response.data.erreur == false) {
                            notyf.confirm(response.data.message);
                            //Reset les données du formulaire à l'original
                            Object.assign(this.$data, this.$options.data())
                            $('.text-zone').parent().removeClass('is-dirty');
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
