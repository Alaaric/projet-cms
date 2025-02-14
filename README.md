# Projet CMS

Ce projet est un CMS (Content Management System) simple développé en PHP avec une architecture MVC. Il permet de créer, modifier et supprimer des pages, ainsi que de gérer les utilisateurs.

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

## Structure du projet

- `src/` : Contient les fichiers source de l'application.
- `public/` : Contient les fichiers accessibles publiquement (point d'entrée de l'application).
- `database/` : Contient le fichier SQL pour la configuration de la base de données.
- `cli/` : Contient les scripts CLI pour générer la base de donée ou créer des utilisateurs.
