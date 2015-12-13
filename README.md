Symfony Tips Samples
=======

Un ensemble de quelques cas d'usages précis et de HOW-TO pour Symfony.

Pour lancer l'application : 

* Installation des dépendances : `composer install`
* Initialiser une base de données et générer le schéma : `php app/console doctrine:schema:update --force`
* Chargement des données minimales : `php app/console doctrine:fixtures:load` 
* Lancer le serveur : `php app/console server:run` 

## Exemples disponibles

1. Utilisation d'une même collection pour contenir différentes versions d'une même entité. Illustré via le lien 
`artwork` et `participant` où un participant peut être de différents types :
  1. Auteur
  2. Publieur

 Exemple visible via l'adresse http://localhost:8000/artworks
 Présente également un cas de "champs dynamiques" dans un formulaire.
 
2. Champ dynamiques de formulaires en fonction de paramètres de BDD (basé sur une notion de collection d'objets)
 Exemple visible via l'adresse http://localhost:8000/subscriptions

