Documentation administrateur
================

Installation
-------------

1. `git clone https://github.com/ChamMach/VirtualWeb.git`
2. `composer update` pour mettre à jour les dépendances
3. `npm install` pour installer tous les packages
4. Créer une base de données nommée `virtual`
5. Lancer la commande `php artisan migrate` pour faire la migration des tables
6. Puis `php artisan db:seed` pour avoir un jeu de données
7. `php artisan cache:clear`


Commandes utiles
----------------
`npm run dev` afin de faire une compilation du code

`npm run watch-poll` pour surveiller les fichiers (exemple : CSS, vue)


> The /.../Web/bootstrap/cache directory must be present and writable.

    php artisan cache:clear

S'il y a un problème à la connexion, toujours vérifier que la structure de la base est à jour. Si ce n'est pas le cas :

- Supprimer la base de données
- Exécuter `php artisan migrate`
- Puis `php artisan db:seed`
