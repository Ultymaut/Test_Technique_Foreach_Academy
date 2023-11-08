# test_foreach

Requis : 
 - Symfony 6.2
 - PHP 8.1
 - Composer
 - SCOOP

Après avoir installer ceci : 
 - Lancer le projet
 - faire un composer install
 - faire la commande : symfony server:start -d
 - Vous devez ensuite créer la BDD et load les fixtures afin d'avoir les excuses et voici les commandes :
   - bin/console doctrine:database:create
   - bin/console make:entity
   - bin/console doctrine:migrations:migrate
   - bin/console doctrine:fixtures:load 
 
Vous arriver donc sur la page "/"
