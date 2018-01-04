<template>
    <dialog class="mdl-dialog" id="modal-example">
        <div class="mdl-dialog__content">
            <h3>Création d'une machine virtuelle</h3>
            <form action="#">
                <div class="mdl-grid">
                    <div class="mdl-cell--5-col">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="nom" pattern="[^_()/><\][\\\x22,;|]+" v-model="vm.nom" required>
                            <label class="mdl-textfield__label" for="nom">Nom de la VM</label>
                        </div>
                    </div>
                    <div class="mdl-cell--5-col mdl-cell--1-offset">
                        <div class="mdl-textfield mdl-js-textfield getmdl-select">
                            <input class="mdl-textfield__input" value="" id="systeme" readonly v-model="vm.systeme" required>
                            <input value="" type="hidden" name="systeme"/>
                            <label class="mdl-textfield__label" for="systeme">Système</label>
                            <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu" for="systeme">
                                <li class="mdl-menu__item" data-val="WI">Windows</li>
                                <li class="mdl-menu__item" data-val="UB">Ubuntu</li>
                                <li class="mdl-menu__item" data-val="CE">Centos</li>
                            </ul>
                        </div>
                    </div>
                    <div class="ram_input mdl-cell--5-col">
                        <label class="">Ram ({{ vm.ram }} Mo)</label>
                        <input class="mdl-slider mdl-js-slider" type="range" min="100" step="100" max="3000" value="200" tabindex="0" v-model="vm.ram" required>
                    </div>
                    <div class="ram_input mdl-cell--5-col mdl-cell--1-offset">
                        <label class="">CPU ({{ vm.cpu }})</label>
                        <input class="mdl-slider mdl-js-slider" type="range" min="1" step="1" max="4" value="1" tabindex="0" v-model="vm.cpu" required>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-cell--12-col">
                      <textarea class="mdl-textfield__input" type="text" rows= "3" id="sample5" required></textarea>
                      <label class="mdl-textfield__label" for="sample5">Description</label>
                    </div>
                </div>
            </form>

        </div>
        <div class="mdl-dialog__actions">
            <button type="button" class="mdl-button">Fermer</button>
            <button type="button" class="mdl-button" disabled>Créer</button>
        </div>
    </dialog>
</template>

<script>
    export default {
        name: 'creation_vm',
        data () {
            return {
                node: {
                    sum: 100,
                },
                vm: {
                    ram: 100,
                    cpu: 1,
                },
            }
        },
        methods: {
            onChange (e) {
                this.node.sum = e.target.value;
            }
        }
    }
</script>
