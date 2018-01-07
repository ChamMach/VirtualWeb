<template>
    <dialog id="dialog_verif" class="mdl-dialog">
            <h3 class="mdl-dialog__title">Vérification</h3>
            <div class="mdl-dialog__content">
                <p>{{ message }}</p>
            </div>
            <div class="mdl-dialog__actions">
                <button type="button" class="mdl-button oui yes_modal_verif" @click="verification">Oui</button>
                <button type="button" class="mdl-button non close_modal_verif">Non</button>
            </div>
    </dialog>
</template>

<script>
    export default {
        props: ["message", "method"],
        data () {
            return {
                //message: this.message,
            }
        },
        methods: {
            verification() {
                this.method()
            }
        }
    }
</script>
