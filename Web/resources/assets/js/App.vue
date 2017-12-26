<template>
    <div class="container">
        <div class="row">
            <div class="col s12 m4 l3" v-if="$route.path !== '/login'">
                <sidebar-menu></sidebar-menu>
            </div>                
            <router-view></router-view>
            <div class="col s12 m8 l9" v-if="$route.path !== '/login'">
                <header-bar></header-bar>
            </div>
        </div>
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
    import Header from './components/Header.vue';
    import Sidebar from './components/Sidebar.vue';
    
    export default{
    }
</script>