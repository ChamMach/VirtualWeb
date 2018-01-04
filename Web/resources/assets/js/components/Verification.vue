<template>
    <dialog id="dialog" class="mdl-dialog">
            <h3 class="mdl-dialog__title">Vérification</h3>
            <div class="mdl-dialog__content">
                <p>{{ message }}</p>
            </div>
            <div class="mdl-dialog__actions">
                <button type="button" class="mdl-button oui">Oui</button>
                <button type="button" class="mdl-button non">Non</button>
            </div>
    </dialog>
</template>

<script>
    export default {
        props: ["message"],
        data () {
            return {
                //message: this.message,
            }
        },
    }
</script>
