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
import Utilisateurs from './components/admin/Utilisateurs.vue';

Vue.use(VueAxios, axios);
Vue.use(VueRouter);

//Creation des routes
console.log(dataArray);

const routes = [
  {
      name: 'Login',
      path: '/connexion',
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
      component: view('Accueil')
  },
  {
      name: 'VM',
      path: '/vm',
      component: view('VM')
  },
  {
      name: 'Conteneurs',
      path: '/conteneur',
      component: view('Conteneur'),
      meta: {
          isAdmin: true
      }
  },
  {
      name: 'Administration',
      path: '/administration',
      component: view('admin/Accueil')
  },
  {
      name: 'Utilisateurs',
      path: '/Utilisateurs',
      component: view('admin/Utilisateurs'),
      meta: {
          isAdmin: true
      }
  }
];
/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * @param  {string}   name     the filename (basename) of the view to load.
 */
function view(name) {
    return function(resolve) {
        require(['./components/' + name + '.vue'], resolve);
    }
};

Vue.component('sidebar-user', require('./components/user/Sidebar.vue'));
Vue.component('sidebar-admin', require('./components/admin/Sidebar.vue'));
Vue.component('header-bar', require('./components/Header.vue'));
Vue.component('tab', require('./components/Tab.vue'));
Vue.component('creation-vm', require('./components/user/CreationVM.vue'));
Vue.component('creation-utilisateur', require('./components/admin/CreationUtilisateur.vue'));

//Creation de l'instance routeur
const router = new VueRouter({
    mode: 'history',
    routes: routes
});

//Avant chaque route
router.beforeEach((to, from, next) => {
    if (from.name !== null) {
        document.body.classList.remove(from.name.toLowerCase())
        document.body.classList.add(to.name.toLowerCase())
    }
    next()
})

new Vue(Vue.util.extend({ router }, App)).$mount('#app');
