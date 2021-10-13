# Le cours sur Symfony

Bienvenue sur ce cours sur Symfony, 
on va faire une branche par chapitre histoire de garder 
les choses propres.

Comme le projet est enregistré avec un .gitignore qui 
ne va pas importer tout le dossier vendor, il faut commencer
par ramener toutes les dépendances :
````
composer install
````

Une fois ceci fait, vous avez un beau projet Symfony prêt
à être lancé.

Comme nous avons rajouté Doctrine, une Database MariaDB 
avec Docker et maintenant des Fixtures !

Tous les utilisateurs ont le mot de passe "password"
````
symfony serve -d
docker-compose up -d
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
````

Si vous voulez voir la liste des migrations avant de 
les faire
````
symfony console doctrine:migrations:list
````

Enfin, si vous voulez faire une query pour voir l'état
de votre DB 
````
symfony console doctrine:query:sql '<votre query>'
````