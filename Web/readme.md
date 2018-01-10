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


Environnement
-------------
Pour lancer le projet proprement il vous faut un environnent représenté par le fichier `.env` à placer à la racine du projet Web

Voici la structure à copier et à adapter selon le votre :

`APP_NAME=VirtualWeb
APP_ENV=local
APP_KEY=base64:6RN81rZmQzqdxuGWNlYBdcMPKpTRfeW5kkH9j99hX/E=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=virtual
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=

SCRIPT_VM_IP=localhost
SCRIPT_VM_PORT=1333`
