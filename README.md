# Plateforme de Partage de Ressources Educatives

## Introduction
Le projet vise à créer une plateforme en ligne permettant aux utilisateurs de partager, rechercher et consulter des ressources éducatives dans divers domaines d'apprentissage. Cette plateforme servira de ressource collaborative pour les étudiants, les enseignants et les passionnés de l'apprentissage, offrant un accès à une variété de matériel éducatif.

## Objectifs
- Offrir une plateforme conviviale et accessible pour la découverte et le partage de ressources éducatives.
- Permettre aux utilisateurs de soumettre leurs propres ressources éducatives pour enrichir la bibliothèque de contenu.
- Faciliter la recherche de ressources par catégorie, mots-clés, niveau d'éducation, etc.
- Encourager la collaboration et l'interaction entre les utilisateurs grâce à des fonctionnalités de notation, de commentaires et de partage.

## Fonctionnalités principales
### 1. Page d'accueil
- Afficher une sélection de ressources éducatives populaires ou récemment ajoutées dans différentes catégories.
- Barre de recherche permettant aux utilisateurs de rechercher des ressources par mot-clé, catégorie, niveau d'éducation, etc.

### 2. Page de ressources
- Présenter une liste de ressources éducatives disponibles avec des titres, des descriptions et des informations sur les auteurs.
- Pagination pour faciliter la navigation entre les différentes pages de ressources.

### 3. Pages individuelles de ressources
- Afficher en détail une ressource spécifique avec des informations complètes, des instructions d'utilisation et des commentaires des utilisateurs.
- Possibilité de télécharger ou de visionner la ressource directement sur la plateforme.

### 4. Page de soumission de ressource
- Permettre aux utilisateurs de soumettre leurs propres ressources éducatives via un formulaire interactif.
- Les champs incluent le titre, la catégorie, la description, les fichiers à télécharger, etc.

### 5. Page de profil utilisateur
- Permettre aux utilisateurs de créer un profil avec des informations personnelles et des statistiques sur leurs contributions.
- Possibilité pour les utilisateurs de modifier leur profil et de gérer leurs ressources soumises.

### 6. Système de notation et de commentaires (optionnel)
- Offrir aux utilisateurs la possibilité de noter et de laisser des commentaires sur les ressources.
- Afficher la note moyenne de chaque ressource basée sur les votes des utilisateurs.

## Technologies utilisées
- HTML, CSS pour le front-end.
- PHP pour le back-end.
- Utilisation de fichiers CSV ou une autre source de données pour stocker les ressources, les commentaires, etc.

## Tutoriel d'installation

### Pré-requis

- **MAMP** : MAMP est une solution de serveur local qui permet d'exécuter PHP et MySQL sur votre machine.
- **Visual Studio Code (VSC)** : Un éditeur de code populaire.
- **Git** : Un système de contrôle de version pour cloner le dépôt GitHub.

### Étape 1 : Installation de MAMP

1. **Télécharger MAMP** :
   - Rendez-vous sur le site officiel de MAMP : [MAMP Downloads](https://www.mamp.info/en/downloads/).
   - Téléchargez et installez la version appropriée pour votre système d'exploitation (Windows ou macOS).

2. **Installer MAMP** :
   - Suivez les instructions d'installation pour votre système d'exploitation.
   - Une fois installé, lancez MAMP.

### Étape 2 : Configuration de MAMP

1. **Démarrer les serveurs** :
   - Ouvrez MAMP et cliquez sur "Start Servers" pour démarrer les serveurs Apache et MySQL.

2. **Configurer les ports** :
   - Par défaut, MAMP utilise les ports 8888 pour Apache et 8889 pour MySQL. Vous pouvez les modifier dans les préférences si nécessaire.

3. **Configurer le dossier racine (htdocs)** :
   - Par défaut, MAMP utilise le dossier `htdocs` dans le répertoire d'installation de MAMP comme racine du serveur. Vous pouvez le changer dans les préférences sous l'onglet "Web Server".

### Étape 3 : Cloner le dépôt Git dans VSC

1. **Ouvrir Visual Studio Code** :
   - Lancez Visual Studio Code.

2. **Cloner le dépôt** :
   - Ouvrez un terminal dans Visual Studio Code (Ctrl + `).
   - Exécutez la commande suivante pour cloner le dépôt GitHub :
     ```bash
     git clone https://github.com/votre-utilisateur/votre-depot.git
     ```
   - Remplacez `votre-utilisateur` et `votre-depot` par les informations appropriées de votre dépôt GitHub.

3. **Déplacer les fichiers clonés** :
   - Déplacez les fichiers clonés dans le dossier `htdocs` de MAMP. Par exemple :
     ```bash
     mv votre-depot /Applications/MAMP/htdocs/ # pour macOS
     mv votre-depot C:\MAMP\htdocs\ # pour Windows
     ```

### Étape 4 : Configuration de la base de données

1. **Accéder à phpMyAdmin** :
   - Ouvrez votre navigateur et allez à `http://localhost/phpmyadmin`.

2. **Importer une base de données** :
   - Cliquez sur "Importer" pour importer une nouvelle base de données.
   - Selectionnez le fichier SQL pour créer la base de données.
   - Cliquez sur "Generate".

### Étape 5 : Configuration de la base de données
1. **Accéder au site** :
   - Ouvrez le lien 'localhost/nom-du-projet'.
   - Remplacez 'nom-du-projet' par le nom de votre projet.

## Interface utilisateur
Conception d'une interface utilisateur intuitive et ergonomique pour faciliter la navigation et la consultation des ressources éducatives.

## Livrables
- Site web fonctionnel et entièrement opérationnel.
- Documentation détaillée du code source et des fonctionnalités.

---

![forthebadge](https://forthebadge.com/images/badges/built-with-love.svg) ![forthebadge](https://forthebadge.com/images/badges/open-source.svg) ![forthebadge](https://forthebadge.com/images/badges/made-with-html.svg) ![forthebadge](https://forthebadge.com/images/badges/made-with-css.svg)
