<template>
    <div v-if="$route.path == '/login'">
        <router-view></router-view>
    </div>
    <div class="wrap" v-else>
        <div class="row">
            <aside class="sidebar">
                <sidebar-menu></sidebar-menu>
            </aside>
            <div class="page-wrapper">
                <header-bar></header-bar>
                <div class="container">
                    <div class="row">
                        <router-view></router-view>
                    </div>
                </div>
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