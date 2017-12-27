import Vue from 'vue';
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import axios from 'axios';
import App from './App.vue';

//Importation des vues
import Login from './components/Login.vue';
import Accueil from './components/Accueil.vue';

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
      name: 'accueil',
      path: '/accueil',
      component: Accueil
  }
];

Vue.component('sidebar-menu', require('./components/Sidebar.vue'));
Vue.component('header-bar', require('./components/Header.vue'));

//Creation de l'instance routeur
const router = new VueRouter({
    mode: 'history',
    routes: routes
});
new Vue(Vue.util.extend({ router }, App)).$mount('#app');