# Projet CMS

Ce projet est un CMS (Content Management System) simple développé en PHP avec une architecture MVC.

Le temps de développement étant assez court, voici une liste non exhaustive des choses manquantes ou inachevées :

- Le CSS n'est pas complètement implémenté et peut sembler bancal ou incomplet sur certaines pages.
- Actuellement, les routes utilisent les méthodes POST au lieu des méthodes PUT et DELETE pour les opérations de mise à jour et de suppression. Cela ne respecte pas les conventions REST, mais nous n'avions pas le temps de retravailler cela.
- La validation des formulaires côté client et côté serveur est minimale voire inexistante.
- La gestion des erreurs pourrait être améliorée pour fournir des messages d'erreur plus clairs et plus utiles.
- Aucun test unitaire n'a été implémenté.
- Très peu de mesures de sécurité ont été mises en place.
- Le versioning de la base de données n'est pas implémenté.
- La création d'utilisateur via un formulaire web n'est pas implémentée et doit être réalisée via des scripts CLI.

## Prérequis

- PHP >= 8.2
- MySQL
- Composer

## Installation

1. Clonez le dépôt :

   ```
   git clone https://github.com/alaric/projet-cms.git
   cd projet-cms
   ```

2. Installez les dépendances avec Composer :

   ```
   composer install
   ```

3. Configurez les variables d'environnement en copiant le fichier .env.sample en .env et en modifiant les valeurs selon votre configuration

4. Initialisez la base de données :

   ```
   bash cli/setup-database.sh
   ```

## Configuration de TinyMCE

Pour utiliser l'éditeur TinyMCE, vous devez obtenir une clé API. Suivez les étapes ci-dessous pour configurer TinyMCE :

1. Rendez-vous sur [TinyMCE](https://www.tiny.cloud/) et inscrivez-vous pour obtenir une clé API.
2. Ajoutez votre clé API dans le fichier `.env` :

   ```
   TINYMCE_API_KEY=your_api_key_here
   ```

## Utilisateurs par défaut

Pour tester l'application, vous pouvez utiliser les utilisateurs par défaut suivants :

- **Admin**

  - Email : `admin`
  - Mot de passe : `test`

- **User**
  - Email : `user`
  - Mot de passe : `test`

## Commandes utiles

### Créer un utilisateur

Pour créer un nouvel utilisateur, exécutez le script suivant :

```
php cli/create-user.php
```

### Lancer le serveur

Pour lancer le serveur local:

```
php -S localhost:8000 -t public
```

## Structure du projet

- `src/` : Contient les fichiers source de l'application.
- `public/` : Contient les fichiers accessibles publiquement (point d'entrée de l'application).
- `database/` : Contient le fichier SQL pour la configuration de la base de données.
- `cli/` : Contient les scripts CLI pour générer la base de donée ou créer des utilisateurs.
