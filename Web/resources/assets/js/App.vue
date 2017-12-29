<template>
    <div v-if="$route.name == 'Login'">
        <router-view></router-view>
    </div>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header" v-else-if="verified == true">
        <header-bar></header-bar>
        <sidebar-admin v-if="status == 'admin'"></sidebar-admin>
        <sidebar-user v-else></sidebar-user>
        
        <main class="mdl-layout__content">
            <div class="page-content">
                <router-view></router-view>
            </div>
        </main>
    </div>
</template>

<style>
    .fade-enter-active, .fade-leave-active {
      transition: opacity .5s
    }
    .fade-enter, .fade-leave-active {
      opacity: 0
    }
</style>

<script>
    export default{
        data () {
            return {
                status: dataArray.status,
                verified: dataArray.verified
            }
        },
    }
</script>
