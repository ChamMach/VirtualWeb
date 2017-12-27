<template>
    <div v-if="$route.path == '/login'">
        <router-view></router-view>
    </div>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header" v-else>
            <header-bar></header-bar>
            <sidebar-menu></sidebar-menu>

            <main class="mdl-layout__content">
              <div class="page-content mdl-grid">
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
    import Header from './components/Header.vue';
    import Sidebar from './components/Sidebar.vue';

    export default{
    }
</script>
