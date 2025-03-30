# Documentation du Logiciel Oboba

## Introduction

Ce document fournit une documentation complète du logiciel Oboba, une application Symfony conçue pour la gestion des câblages industriels, incluant câbles, connecteurs et leurs composants. Il couvre la structure du projet, les entités, les relations, les fonctionnalités, l'interface utilisateur, les aspects techniques, et les scénarios d'utilisation.

## Structure du Répertoire

Le projet Oboba est structuré comme suit :


jp-zaclev-oboba/
├── README.md // Informations générales sur le projet
├── compose.override.yaml // Configuration Docker spécifique à l'environnement
├── compose.yaml // Configuration Docker pour l'ensemble du projet
├── composer.json // Dépendances PHP et informations sur le projet
├── composer.lock // Liste des versions exactes des dépendances installées
├── generate-entities.php // Script pour générer les entités Doctrine à partir de la base de données
├── oboba_demo.sql // Dump SQL de démonstration
├── populate_oboba_test.sql // Script SQL pour peupler la base de données de test
├── serveur_start.sh // Script shell pour démarrer le serveur Symfony
├── symfony.lock // Verrouillage des composants Symfony utilisés
├── TODO // Liste des tâches à faire et des fonctionnalités à implémenter
├── bin/ // Exécutables
│ └── console // Console Symfony
├── config/ // Fichiers de configuration de Symfony
│ ├── bundles.php // Liste des bundles activés
│ ├── preload.php // Script de préchargement pour améliorer les performances
│ ├── routes.yaml // Configuration des routes
│ ├── services.yaml // Définition des services
│ ├── packages/ // Configuration des bundles (cache, doctrine, framework, etc.)
│ │ ├── cache.yaml
│ │ ├── doctrine.yaml
│ │ ├── doctrine_migrations.yaml
│ │ ├── framework.yaml
│ │ ├── knp_paginator.yaml
│ │ ├── monolog.yaml
│ │ ├── routing.yaml
│ │ ├── security.yaml
│ │ ├── sensio_framework_extra.yaml
│ │ ├── translation.yaml
│ │ ├── twig.yaml
│ │ ├── validator.yaml
│ │ ├── web_profiler.yaml
│ │ └── dev/
│ │ └── monolog.yaml
│ └── routes/ // Routes spécifiques (annotations, framework, web profiler)
│ ├── annotations.yaml
│ ├── framework.yaml
│ └── web_profiler.yaml
├── doc/ // Documentation et spécifications
│ ├── data.sql // Données SQL pour la documentation
│ ├── MANUEL-1.md // Manuel utilisateur
│ ├── schema.sql // Schéma SQL de la base de données
│ ├── schema2.sql // Schéma alternatif de la base de données
│ ├── spec-ihm.txt // Spécifications de l'interface utilisateur
│ ├── SPECIFICATIONS - BD.md // Spécifications de la base de données
│ ├── SPECIFICATIONS-2.md // Spécifications du logiciel
│ ├── SPECIFICATIONS-3.md // Spécifications du logiciel (version récente)
│ ├── SPECIFICATIONS.md // Spécifications du logiciel (version antérieure)
│ ├── SPECIFICATIONS.tex // Spécifications du logiciel (format LaTeX)
│ ├── test schéma graphique.html // Fichier HTML de test pour un schéma graphique
│ └── uml/ // Diagrammes UML
│ ├── classes.txt // Diagramme de classes (format PlantUML)
│ ├── plant.sh // Script shell pour générer les diagrammes UML
│ └── usecases.txt // Diagramme des cas d'utilisation (format PlantUML)
├── migrations/ // Migrations Doctrine
│ ├── Version20250327181706.php // Migration spécifique
│ └── .gitignore
├── public/ // Fichiers publics (CSS, JavaScript, images)
│ ├── index.php // Point d'entrée de l'application
│ ├── css/
│ │ └── style.css // Styles CSS
│ └── images/
│ └── Oboba-logo1.webp // Logo Oboba
├── src/ // Code source de l'application
│ ├── Kernel.php // Classe Kernel de Symfony
│ ├── Command/ // Commandes de la console Symfony
│ │ ├── AddUserCommand.php // Commande pour ajouter un utilisateur
│ │ └── CreateAdminCommand.php // Commande pour créer un administrateur
│ ├── Controller/ // Contrôleurs Symfony
│ │ ├── BaseController.php
│ │ ├── BornierController.php
│ │ ├── CableController.php
│ │ ├── CatalogueModeleBorniersController.php
│ │ ├── CatalogueModeleCablesController.php
│ │ ├── CatalogueModeleConnecteursController.php
│ │ ├── CatalogueProjetBorniersController.php
│ │ ├── CatalogueProjetCablesController.php
│ │ ├── CatalogueProjetConnecteursController.php
│ │ ├── ConnecteurController.php
│ │ ├── HomeController.php
│ │ ├── LocalisationController (Copie).php
│ │ ├── LocalisationController.php
│ │ ├── ProjetController.php
│ │ ├── SecurityController.php
│ │ ├── UtilisateurController.php
│ │ └── WireSignalController.php
│ ├── DataFixtures/ // Fixtures Doctrine
│ │ └── AppFixtures.php // Fixtures initiales
│ ├── Doctrine/ // Types Doctrine personnalisés
│ │ └── EnumType.php // Type pour les enums
│ ├── Entity/ // Entités Doctrine
│ │ ├── Borne.php
│ │ ├── Bornier.php
│ │ ├── Cable.php
│ │ ├── CatalogueModeleBorniers.php
│ │ ├── CatalogueModeleCables.php
│ │ ├── CatalogueModeleConnecteurs.php
│ │ ├── CatalogueProjetBorniers.php
│ │ ├── CatalogueProjetCables.php
│ │ ├── CatalogueProjetConnecteurs.php
│ │ ├── Conducteur.php
│ │ ├── Connecteur.php
│ │ ├── Contact.php
│ │ ├── Equipement.php
│ │ ├── Localisation.php
│ │ ├── Projet.php
│ │ ├── ProjetUtilisateur.php
│ │ ├── Utilisateur.php
│ │ └── WireSignal.php
│ ├── Form/ // Types de formulaire Symfony
│ │ ├── AssignerProprietaireType.php
│ │ ├── BornierFilterType.php
│ │ ├── BornierType.php
│ │ ├── CableFilterType.php
│ │ ├── CableType.php
│ │ ├── CatalogueModeleBorniersFilterType.php
│ │ ├── CatalogueModeleBorniersType.php
│ │ ├── CatalogueModeleCablesFilterType.php
│ │ ├── CatalogueModeleCablesType.php
│ │ ├── CatalogueModeleConnecteursFilterType.php
│ │ ├── CatalogueModeleConnecteursType.php
│ │ ├── CatalogueProjetBorniersFilterType.php
│ │ ├── CatalogueProjetBorniersType.php
│ │ ├── CatalogueProjetCablesFilterType.php
│ │ ├── CatalogueProjetCablesType.php
│ │ ├── CatalogueProjetConnecteursFilterType.php
│ │ ├── CatalogueProjetConnecteursType.php
│ │ ├── ConnecteurFilterType.php
│ │ ├── ConnecteurType.php
│ │ ├── LocalisationFilterType.php
│ │ ├── LocalisationType (Copie).php
│ │ ├── LocalisationType.php
│ │ ├── ProjetRecrutementType.php
│ │ ├── ProjetType.php
│ │ ├── UtilisateurType.php
│ │ ├── WireSignalFilterType.php
│ │ └── WireSignalType.php
│ ├── Repository/ // Repositories Doctrine
│ │ ├── CatalogueModeleBorniersRepository.php
│ │ ├── CatalogueModeleCablesRepository.php
│ │ ├── CatalogueModeleConnecteursRepository.php
│ │ └── ProjetRepository.php
│ └── Security/ // Composants de sécurité Symfony
│ └── ProjetVoter.php // Voter personnalisé pour les projets
├── templates/ // Templates Twig
│ ├── base.html.twig // Template de base
│ ├── PPPPPlist.html.twig
│ ├── bornier/
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ └── new.html.twig
│ ├── cable/
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ └── new.html.twig
│ ├── catalogue_modele_borniers/
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ └── new.html.twig
│ ├── catalogue_modele_cables/
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ └── new.html.twig
│ ├── catalogue_modele_connecteurs/
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ └── new.html.twig
│ ├── catalogue_projet_borniers/
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ └── new.html.twig
│ ├── catalogue_projet_cables/
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ └── new.html.twig
│ ├── catalogue_projet_connecteurs/
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ └── new.html.twig
│ ├── connecteur/
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ └── new.html.twig
│ ├── home/
│ │ └── index.html.twig
│ ├── layout/
│ │ └── project_layout.html.twig
│ ├── localisation/
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ └── new.html.twig
│ ├── projet/
│ │ ├── assigner_proprietaire.html.twig
│ │ ├── edit.html.twig
│ │ ├── list.html.twig
│ │ ├── mes_projets.html.twig
│ │ ├── mes_projets_new.html.twig
│ │ ├── new.html.twig
│ │ ├── recrutement.html.twig
│ │ └── show.html.twig
│ ├── security/
│ │ └── login.html.twig
│ ├── utilisateur/
│ │ ├── edit.html.twig
│ │ ├── gestion.html.twig
│ │ ├── new.html.twig
│ │ └── show.html.twig
│ └── wire_signal/
│ ├── edit.html.twig
│ ├── list.html.twig
│ └── new.html.twig
└── translations/ // Fichiers de traduction
└── .gitignore

## Fichiers Clés et Leur Fonction

*   **`README.md`**: Contient des informations générales sur le projet, telles que les instructions d'installation et la configuration initiale.  Il indique notamment qu'un utilisateur administrateur par défaut est créé au déploiement (email: admin@default.com, mot de passe: admin123).
*   **`compose.yaml` et `compose.override.yaml`**: Utilisés pour définir et configurer les services Docker de l'application, comme la base de données PostgreSQL.
*   **`composer.json` et `composer.lock`**: Gèrent les dépendances PHP du projet, assurant la reproductibilité de l'environnement.
*   **`generate-entities.php`**: Script PHP pour générer automatiquement les entités Doctrine à partir du schéma de base de données.
*   **`oboba_demo.sql` et `populate_oboba_test.sql`**: Fichiers SQL pour initialiser et remplir la base de données avec des données de démonstration ou de test.
*   **`serveur_start.sh`**: Script pour démarrer le serveur Symfony.
*   **`symfony.lock`**: Gère les versions des composants Symfony utilisés.
*   **`TODO`**: Liste des tâches à faire, des améliorations potentielles et des corrections à apporter au logiciel.
*   **`src/`**: Contient le code source de l'application, incluant les entités, les contrôleurs, les formulaires, les repositories, et les composants de sécurité.
*   **`templates/`**: Contient les templates Twig utilisés pour rendre l'interface utilisateur.
*   **`config/`**: Contient les fichiers de configuration de Symfony, notamment les routes, les services, et les paramètres des bundles.
*   **`migrations/`**: Contient les migrations Doctrine, utilisées pour faire évoluer le schéma de la base de données.
*   **`doc/`**: Contient la documentation du projet, incluant les spécifications techniques, le manuel utilisateur, et les diagrammes UML.

## Dépendances

*   **PHP**: Version >= 7.2.5
*   **Symfony**: Version 5.4
*   **Doctrine ORM**: Version 2.9
*   **KnpPaginatorBundle**: Version ^5.9
*   **PostgreSQL**: (Configurée via Docker)

## Entités

### Utilisateur

*   Représente un utilisateur de l'application.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul)
    *   `email` (string, non nul, unique)
    *   `password` (string, non nul)
    *   `roles` (array, type JSON)
*   Relations:
    *   `OneToMany` avec `ProjetUtilisateur`.

### Projet

*   Représente un projet.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul)
    *   `dateHeureCreation` (datetime, non nul)
    *   `dateHeureDerniereModification` (datetime, non nul)
*   Relations:
    *   `OneToMany` avec `ProjetUtilisateur`, `Cable`, `Connecteur`, `WireSignal`, `Bornier`, `Equipement`, `CatalogueProjetCables`, `CatalogueProjetConnecteurs`, `CatalogueProjetBorniers`, `Localisation`.

### ProjetUtilisateur

*   Associe un utilisateur à un projet avec un rôle spécifique.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `projet` (relation ManyToOne vers Projet, non nul)
    *   `utilisateur` (relation ManyToOne vers Utilisateur, non nul)
    *   `role` (string, enum: proprietaire, concepteur, lecteur, non nul)

### Cable

*   Représente un cable.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul)
    *   `longueur` (int, nullable)
    *   `projet` (relation ManyToOne vers Projet, non nul)
    *   `catalogueProjetCables` (relation ManyToOne vers CatalogueProjetCables, nullable)
*   Relations:
    *   `OneToMany` avec Conducteur.

### Connecteur

*   Représente un connecteur.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul)
    *   `projet` (relation ManyToOne vers Projet, non nul)
    *   `catalogueProjetConnecteurs` (relation ManyToOne vers CatalogueProjetConnecteurs, nullable)
    *   `equipement` (relation ManyToOne vers Equipement, nullable)
*   Relations:
    *   `OneToMany` avec Contact.

### Conducteur

*   Représente un conducteur au sein d'un cable.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `cable` (relation ManyToOne vers Cable, non nul)
    *   `attribut` (string, non nul)
    *   `wireSignal` (relation ManyToOne vers WireSignal, nullable)
    *   `borneSource` (relation ManyToOne vers Borne, nullable)
    *   `borneDestination` (relation ManyToOne vers Borne, nullable)
    *   `contactSource` (relation ManyToOne vers Contact, nullable)
    *   `contactDestination` (relation ManyToOne vers Contact, nullable)

### Borne

*   Représente une borne au sein d'un bornier.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `bornier` (relation ManyToOne vers Bornier, non nul)
    *   `identification` (string, non nul)

### Bornier

*   Représente un bornier.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul)
    *   `catalogueProjetBorniers` (relation ManyToOne vers CatalogueProjetBorniers, non nul)
    *   `projet` (relation ManyToOne vers Projet, non nul)
    *   `localisation` (relation ManyToOne vers Localisation, non nul)
*   Relations:
    *   `OneToMany` avec Borne.

### Equipement

*   Représente un équipement.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul)
    *   `reference` (string, non nul)
    *   `projet` (relation ManyToOne vers Projet, non nul)
*   Relations:
    *   `OneToMany` avec Connecteur.

### Contact

*   Représente un contact sur un connecteur.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `connecteur` (relation ManyToOne vers Connecteur, non nul)
    *   `identifiant` (string, non nul)
    *   `type` (string, enum: emission, reception, emission\_reception, non nul)

### WireSignal

*   Représente un signal (analogique ou numérique).
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul, unique)
    *   `type` (string, enum: analogique, digital, non nul)
    *   `details` (string, nullable)
    *   `projet` (relation ManyToOne vers Projet, non nul)

### CatalogueModeleCables

*   Représente un modèle de câble dans le catalogue.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul, unique)
    *   `type` (string, non nul)
    *   `nombreConducteursMax` (int, non nul)
    *   `prixUnitaire` (decimal, non nul)

### CatalogueModeleConnecteurs

*   Représente un modèle de connecteur dans le catalogue.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul, unique)
    *   `nombreContacts` (int, non nul)
    *   `type` (string, non nul)
    *   `prixUnitaire` (decimal, non nul)

### CatalogueModeleBorniers

*   Représente un modèle de bornier dans le catalogue.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul, unique)
    *   `nombreBornes` (int, non nul)
    *   `caracteristiques` (string, nullable)
    *   `prixUnitaire` (decimal, non nul)

### CatalogueProjetCables

*   Représente un type de câble spécifique à un projet.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `projet` (relation ManyToOne vers Projet, non nul)
    *   `nom` (string, non nul)
    *   `type` (string, non nul)
    *   `nombreConducteursMax` (int, non nul)
    *   `prixUnitaire` (decimal, non nul)

### CatalogueProjetConnecteurs

*   Représente un type de connecteur spécifique à un projet.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `projet` (relation ManyToOne vers Projet, non nul)
    *   `nom` (string, non nul)
    *   `nombreContacts` (int, non nul)
    *   `type` (string, non nul)
    *   `prixUnitaire` (decimal, non nul)

### CatalogueProjetBorniers

*   Représente un type de bornier spécifique à un projet.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `projet` (relation ManyToOne vers Projet, non nul)
    *   `nom` (string, non nul)
    *   `nombreBornes` (int, non nul)
    *   `caracteristiques` (string, nullable)
    *   `prixUnitaire` (decimal, non nul)

### Localisation

*   Représente un emplacement physique dans un projet.
*   Champs:
    *   `id` (int, clé primaire, auto-incrémentée)
    *   `nom` (string, non nul, unique)
    *   `x` (float, nullable)
    *   `y` (float, nullable)
    *   `z` (float, nullable)
    *   `projet` (relation ManyToOne vers Projet, non nul)

## Fonctionnalités

### Gestion des Utilisateurs
CRUD complet pour les utilisateurs avec gestion des rôles (administrateur, utilisateur). Accessible seulement aux administrateurs.

### Gestion des Projets
*   Consultation de la liste des projets pour les utilisateurs connectés.
*   Création, modification et suppression des projets (accessible aux administrateurs).
*   Chaque utilisateur a un rôle (lecteur, concepteur, propriétaire) pour chaque projet.

### Gestion des Câbles et Connecteurs
*   Consultation des listes de câbles et de connecteurs pour un projet donné.
*   Création, modification et suppression des câbles et connecteurs (accessible aux concepteurs et propriétaires du projet).
*   Filtrage des listes par nom, type, etc.
*   Exportation des données en CSV.

### Gestion des Catalogues
*   Gestion des catalogues modèles (câbles, connecteurs, borniers) accessibles aux administrateurs.
*   Les catalogues projet sont créés automatiquement à la création d'un projet.

### Liste des fonctionnalités CRUD implémentées

*   Utilisateur : create, read, update, delete.
*   Projet : read, create, update, delete (administrateur only).
*   Cable : create, read, update, delete.
*   Connecteur : create, read, update, delete.
*   Catalogue modèle bornier: create, read, update, delete (administrateur only).
*   Catalogue modèle connecteur: create, read, update, delete (administrateur only).
*   Catalogue modèle cable: create, read, update, delete (administrateur only).

## Interface Utilisateur
*   Interface web responsive basée sur Bootstrap.
*   Tableaux de données paginés.
*   Formulaires pour la création et la modification des entités.
*   Messages flash pour notifier les succès et les erreurs.

## Aspects Techniques

*   Application Symfony 5.4.
*   Base de données MariaDB.
*   ORM Doctrine.
*   Contraintes de validation sur les entités.
*   Gestion des droits d'accès via le composant Security de Symfony.
*   Utilisation de Docker pour l'environnement de développement.

## Améliorations et Fonctionnalités à venir (Liste TODO)
* CRUD pour CatalogueModeleBorniers
* Dans mes projets, ajouter l'accès à la gestion CRUD des catalogues projet
* une page spécifique à un projet avec les actions possibles (actuelles et prévisionnelles).
* Une création d'un projet implique l'import des catalogues modèles dans les catalogues projet.
* CRUD catalogue cable du projet
* CRUD catalogue connecteur du projet
* CRUD catalogue bornier du projet
* Ajouter la suppression de masse par une liste à cocher dans toutes les listes.
    * catalogue projet
    * catalogue modèle
    * projets
    * utilisateurs
* Ajouter au CRUD cable du projet l'import en cours de vie du catalogue modèle (ajout d'articles)
 Dans un catalogue projet, à la création du projet il a été peuplé par le contenue du catalogue modèle. Ensuite ils sont dissociés. Si un administrateur enrichi un catalogue modèle, le propriétaire ou le concepteur du projet doit avoir à disposition une fonctionnalité d'import des articles du catalogue modèle qui manquent dans le catalogue projet. Les autres articles existants du catalogue projet doivent restés inchangés, même s'ils ont été modifié depuis la création. 
Précision, dans le message de succès, préciser "combien" d'articles ont été importés.
Attention, je veux que les imports soient spécifiques aux catalogues cables, borniers et connecteurs. C'est à l'utilisateur de choisir dans quel catalogue il veut agir. Le bouton de déclenchement d'une telle action est à positionner dans la visualisation d'une liste d'un catalogue. 
* Ajouter au CRUD connecteur du projet l'import en cours de vie du catalogue modèle (ajout d'articles)
* Ajouter au CRUD bornier du projet l'import en cours de vie du catalogue modèle (ajout d'articles)

* Ajouter une CRUD pour les signaux.
J'ai besoin d'un CRUD pour les signaux. Comme pour les câbles par exemple, j'ai besoin d'une liste avec un filtre, des cases à cocher pour effacer et une pagination. Tu trouveras l'entité ci-dessous. Attention, la table s'appelle wire_signal, parce que signal est un mot clé en SQL. Mais dans l'IHM, il faut bien parler de signal (signaux au pluriel) pour être compréhensible par l'utilisateur. Pour le moment, je n'ai pas de controleur. Allons-y progressivement, fichier par fichier.

* Ajouter un CRUD pour les borniers.
* Ajouter un CRUD pour les équipements.
* Ajouter un CRUD pour les emplacements.

* un câble est un objet composite formé de conducteurs.
* un bornier est un objet composite formé de bornes.

* Recrutement: ajouter un bouton pour modifier l'habilitation d'un utilisateur
* Dans la visu projet: créer une zone "catalogue" avec tous les boutons

* Mettre en place une mécanique pour les compositions:	
	borniers -> bornes
	cables -> conducteurs
	connecteurs -> contacts
* Réfléchir à l'IHM pour faire les connections 

* Ajouter les export CSV dans les catalogues modèles
* Ajouter un import CSV dans les catalogues modèles
* Faire une sauvegarde totale d'un projet
* Faire une restoration totale d'un projet

* La destruction d'un projet implique l'effacement des catalogues projet.

pour réflexion uniquement, câbles, connecteurs et borniers sont des objets composites puisque composés respectivement de conducteurs, de contacts et de bornes. Et donc à la création d'un de ces objets, il faut créer les composants et les associer. Ensuite il faut pouvoir les présenter à l'écran à partir d'une liste. C'est une action qui doit être disponible aussi pour un lecteur, pas seulement un concepteur et un propriétaire.
IGNORE_WHEN_COPYING_START
content_copy
download
Use code with caution.
IGNORE_WHEN_COPYING_END
