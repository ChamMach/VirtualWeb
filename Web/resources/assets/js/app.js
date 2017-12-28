import Vue from 'vue';
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import axios from 'axios';
import App from './App.vue';

//Importation des vues
import Login from './components/Login.vue';
import Accueil from './components/Accueil.vue';
import VM from './components/VM.vue';
import Conteneur from './components/Conteneur.vue';

Vue.use(VueAxios, axios);
Vue.use(VueRouter);

//Creation des routes
const routes = [
  {
      name: 'Login',
      path: '/login',
      component: Login
  },
  {
      name: 'logout',
      path: 'deconnexion'
  },
  {
      name: 'Connexion',
      path: 'connexion'
  },
  {
      name: 'Accueil',
      path: '/accueil',
      component: Accueil
  },
  {
      name: 'VM',
      path: '/vm',
      component: VM
  },
  {
      name: 'Conteneurs',
      path: '/conteneur',
      component: Conteneur
  }
];

Vue.component('sidebar-menu', require('./components/user/Sidebar.vue'));
Vue.component('header-bar', require('./components/Header.vue'));
Vue.component('tab', require('./components/Tab.vue'));

//Creation de l'instance routeur
const router = new VueRouter({
    mode: 'history',
    routes: routes
});
new Vue(Vue.util.extend({ router }, App)).$mount('#app');
