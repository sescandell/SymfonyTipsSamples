Symfony Tips Samples
=======

Un ensemble de quelques cas d'usages précis et de HOW-TO pour Symfony.

Pour lancer l'application : 

* Installation des dépendances : `composer install`
* Initialiser une base de données et générer le schéma : `php app/console doctrine:schema:update --force` 
* Lancer le serveur : `php app/console server:run` 

## Exemples disponibles

1. Utilisation d'une même collection pour contenir différentes version d'une même entité. Illustré via le lien 
`artwork` et `participant` où un participant peut être de différents types :
  1. Auteur
  2. Publieur

 Exemple visible via l'adresse http://localhost:8000/artworks

