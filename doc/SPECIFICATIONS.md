# Spécifications du logiciel Oboba

*Date : 18 mars 2025*  
*Projet : Gestion des câbles et connecteurs dans une application Symfony offline*

## Table des matières
1. [Introduction](#introduction)
2. [Objectifs](#objectifs)
3. [Entités et Relations](#entités-et-relations)
   - [Utilisateur](#utilisateur)
   - [Projet](#projet)
   - [Signal](#signal)
   - [Catalogue Modèle des Câbles](#catalogue-modèle-des-câbles)
   - [Catalogue Projet des Câbles](#catalogue-projet-des-câbles)
   - [Câble](#câble)
   - [Conducteur](#conducteur)
   - [Catalogue Modèle des Borniers](#catalogue-modèle-des-borniers)
   - [Catalogue Projet des Borniers](#catalogue-projet-des-borniers)
   - [Bornier](#bornier)
   - [Borne](#borne)
   - [Catalogue Modèle des Connecteurs](#catalogue-modèle-des-connecteurs)
   - [Catalogue Projet des Connecteurs](#catalogue-projet-des-connecteurs)
   - [Connecteur](#connecteur)
   - [Contact](#contact)
   - [Equipement](#equipement)
   - [Relations](#relations)
4. [Fonctionnalités Principales](#fonctionnalités-principales)
   - [Gestion des Utilisateurs](#gestion-des-utilisateurs)
   - [Consultation de la Liste des Projets](#consultation-de-la-liste-des-projets)
   - [Consultation de la Liste des Câbles](#consultation-de-la-liste-des-câbles)
   - [Consultation de la Liste des Signaux](#consultation-de-la-liste-des-signaux)
   - [Saisie et Modification des Données (Concepteur)](#saisie-et-modification-des-données-concepteur)
   - [Gestion des Catalogues](#gestion-des-catalogues)
   - [Gestion des Erreurs](#gestion-des-erreurs)
   - [Règles de Propagation des Signaux](#règles-de-propagation-des-signaux)
   - [Fonctionnalités Avancées](#fonctionnalités-avancées)
5. [Spécifications de l’Interface Utilisateur](#spécifications-de-linterface-utilisateur)
6. [Exigences Non Fonctionnelles](#exigences-non-fonctionnelles)
7. [Contraintes Techniques](#contraintes-techniques)
8. [Architecture Technique](#architecture-technique)
9. [Implémentation Actuelle](#implémentation-actuelle)
   - [Routes](#routes)
   - [Formulaires](#formulaires)
   - [Templates](#templates)
   - [Contraintes et Sécurité](#contraintes-et-sécurité)
10. [Scénarios](#scénarios)
11. [Conclusion](#conclusion)

---

## Introduction

Le logiciel Oboba vise à simplifier la conception, la gestion et la documentation des câblages industriels en offrant une interface intuitive et une gestion fine des droits d’accès. Ce document décrit les spécifications détaillées d’un logiciel de conception et de gestion de câblage industriel, incluant les câbles, borniers, et connecteurs, ainsi que leurs composants (conducteurs, bornes, contacts) et signaux (analogiques ou numériques).

L’implémentation actuelle se concentre sur la gestion des utilisateurs, projets, câbles et connecteurs dans une application Symfony offline, avec des fonctionnalités de liste paginée, ajout, modification, suppression, filtrage, et exportation en CSV.

---

## Objectifs

- Permettre la gestion de multiples projets indépendants avec des catalogues spécifiques.
- Offrir une interface utilisateur responsive et intuitive basée sur Bootstrap.
- Gérer les droits d’accès via des rôles (lecteur, concepteur, propriétaire, administrateur).
- Supporter la consultation, la saisie, et l’exportation des données de câblage.

---

## Entités et Relations

### Utilisateur
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255)`
  - `email` : `VARCHAR(255)`
  - `password` : `VARCHAR(255)`
  - `roles` : `JSON`
- **Comportements** : Se connecter, consulter les projets autorisés, gérer les utilisateurs (administrateur uniquement).
- **Implémentation** :
  - Table : `utilisateur`
  - Relations : `OneToMany` avec `ProjetUtilisateur`
- **Statut** : Implémenté avec gestion complète (CRUD, authentification).

### Projet
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `description` : `TEXT NULL`
  - `date_heure_creation` : `DATETIME`
- **Implémentation** :
  - Table : `projet`
  - Relations : `OneToMany` avec `Cable`, `Connecteur`, `ProjetUtilisateur`
- **Statut** : Implémenté (liste des projets par utilisateur disponible).

### Signal
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255)`
  - `type` : `ENUM('analogique', 'digital')`
  - `details` : `VARCHAR(255)` (ex. "tension 24V")
  - `id_projet` : `INT` (référence à Projet)
- **Relations** : Lié à un ou plusieurs Conducteurs.
- **Statut** : Non implémenté.

### Catalogue Modèle des Câbles
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255)`
  - `type` : `VARCHAR(255)` (ex. "coaxial")
  - `nombre_conducteurs_max` : `INT`
  - `prix_metre` : `DECIMAL(10,2)`
- **Statut** : Non implémenté.

### Catalogue Projet des Câbles
- **Table** : `catalogue_projet_cables`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_projet` : `INT NOT NULL` (référence à Projet)
  - `nom` : `VARCHAR(255) NOT NULL`
- **Relations** : Référencé par les Câbles du projet.
- **Statut** : Implémenté.

### Câble
- **Table** : `cable`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `longueur` : `INT NOT NULL`
  - `id_projet` : `INT NOT NULL` (référence à Projet)
  - `id_catalogue_projet_cable` : `INT NULL` (référence à Catalogue Projet des Câbles)
- **Relations** : Contient un ou plusieurs Conducteurs.
- **Statut** : Implémenté (sans Conducteurs pour l’instant).

### Conducteur
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_cable` : `INT` (référence à Câble)
  - `attribut` : `VARCHAR(255)` (ex. "couleur: rouge")
  - `id_extremite_source` : `INT NULL` (référence à Borne ou Contact)
  - `id_extremite_destination` : `INT NULL` (référence à Borne ou Contact)
- **Relations** : Transporte un Signal.
- **Statut** : Non implémenté.

### Catalogue Modèle des Borniers
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255)`
  - `nombre_bornes` : `INT`
  - `caracteristiques` : `VARCHAR(255)`
  - `prix_unitaire` : `DECIMAL(10,2)`
- **Statut** : Non implémenté.

### Catalogue Projet des Borniers
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_projet` : `INT` (référence à Projet)
  - `nom` : `VARCHAR(255)`
  - `nombre_bornes` : `INT`
  - `caracteristiques` : `VARCHAR(255)`
  - `prix_unitaire` : `DECIMAL(10,2)`
- **Statut** : Non implémenté.

### Bornier
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255)`
  - `id_catalogue_projet_bornier` : `INT` (référence à Catalogue Projet des Borniers)
  - `id_projet` : `INT` (référence à Projet)
  - `localisation` : `VARCHAR(255)`
- **Relations** : Contient une ou plusieurs Bornes.
- **Statut** : Non implémenté.

### Borne
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_bornier` : `INT` (référence à Bornier)
  - `identification` : `VARCHAR(255)` (ex. "1")
- **Statut** : Non implémenté.

### Catalogue Modèle des Connecteurs
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255)`
  - `nombre_contacts` : `INT`
  - `type` : `VARCHAR(255)`
  - `prix_unitaire` : `DECIMAL(10,2)`
- **Statut** : Non implémenté.

### Catalogue Projet des Connecteurs
- **Table** : `catalogue_projet_connecteurs`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_projet` : `INT NOT NULL` (référence à Projet)
  - `nom` : `VARCHAR(255) NOT NULL`
  - `nombre_contacts` : `INT NOT NULL`
  - `type` : `VARCHAR(50) NOT NULL`
  - `prix_unitaire` : `DECIMAL(10,2) NOT NULL`
- **Relations** : Référencé par les Connecteurs du projet.
- **Statut** : Implémenté.

### Connecteur
- **Table** : `connecteur`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `id_projet` : `INT NOT NULL` (référence à Projet)
  - `id_catalogue_projet_connecteur` : `INT NOT NULL` (référence à Catalogue Projet des Connecteurs)
- **Relations** : Contient un ou plusieurs Contacts (non implémenté).
- **Statut** : Implémenté (sans Contacts ni localisation pour l’instant).

### Contact
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_connecteur` : `INT` (référence à Connecteur)
  - `identifiant` : `VARCHAR(255)` (ex. "A")
  - `type` : `ENUM('emission', 'reception', 'emission_reception')`
- **Statut** : Non implémenté.

### Equipement
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255)`
  - `reference` : `VARCHAR(255)`
  - `id_projet` : `INT` (référence à Projet)
- **Relations** : Peut être connecté à un ou plusieurs Connecteurs.
- **Statut** : Non implémenté.

### Relations
- **Implémentées** : `Utilisateur` → `ProjetUtilisateur`, `Projet` → `Cable`, `Projet` → `Connecteur`, `Cable` → `CatalogueProjetCables`, `Connecteur` → `CatalogueProjetConnecteurs`.
- **Non implémentées** : Conducteurs, Borniers, Contacts, Signaux, Equipements.

---

## Fonctionnalités Principales

### Gestion des Utilisateurs
- **Spécification** : Comptes avec rôles (administrateur, propriétaire, concepteur, lecteur).
- **Implémentation** :
  - CRUD complet via `UtilisateurController`.
  - Rôles `ROLE_ADMIN` et `ROLE_USER` gérés, authentification par formulaire.
- **Statut** : Implémenté.

### Consultation de la Liste des Projets
- **Spécification** : Liste avec détails et suppression par l’administrateur.
- **Implémentation** :
  - Liste des projets de l’utilisateur connecté via `/projets/mes-projets`.
  - Pas encore de suppression par l’administrateur.
- **Statut** : Partiellement implémenté.

### Consultation de la Liste des Câbles
- **Spécification** : Liste avec filtres (nom, type, prix max).
- **Implémentation** :
  - Liste paginée (10 par page).
  - Filtres : `nom`, `longueurMin`, `longueurMax`, `catalogueProjetCables`.
  - Colonnes : `Nom`, `Longueur`, `Catalogue Projet`, `Actions`.
- **Statut** : Implémenté (sans conducteurs ni prix max).

### Consultation de la Liste des Signaux
- **Spécification** : Liste avec filtres et détails de transit.
- **Statut** : Non implémenté.

### Saisie et Modification des Données (Concepteur)
- **Spécification** : CRUD pour câbles, borniers, connecteurs, signaux.
- **Implémentation** :
  - **Câbles** : Ajout, modification, suppression avec catalogue optionnel.
  - **Connecteurs** : Ajout, modification, suppression avec catalogue obligatoire.
- **Statut** : Partiellement implémenté (câbles et connecteurs uniquement).

### Gestion des Catalogues
- **Spécification** : Catalogues modèles et spécifiques aux projets.
- **Implémentation** :
  - `CatalogueProjetCables` et `CatalogueProjetConnecteurs` spécifiques aux projets.
- **Statut** : Partiellement implémenté (catalogues modèles non faits).

### Gestion des Erreurs
- **Spécification** : Messages d’erreur pour références invalides, connexions impossibles, etc.
- **Implémentation** :
  - Validation `NotBlank` pour `nom` et `catalogueProjetConnecteurs` (connecteurs).
  - Confirmation popup pour suppression.
- **Statut** : Partiellement implémenté.

### Règles de Propagation des Signaux
- **Spécification** : Propagation avec détection des conflits.
- **Statut** : Non implémenté.

### Fonctionnalités Avancées
- **Spécification** : Export CSV/PDF, rapports, historique.
- **Implémentation** :
  - Export CSV pour câbles et connecteurs.
- **Statut** : Partiellement implémenté (CSV uniquement).

---

## Spécifications de l’Interface Utilisateur
- **Spécification** : Tableaux triables, formulaires déroulants, messages temporaires.
- **Implémentation** :
  - Tableaux paginés avec Bootstrap.
  - Formulaires simples avec `form_widget`.
  - Messages flash pour succès/erreurs.
- **Statut** : Implémenté.

---

## Exigences Non Fonctionnelles
- **Performance** : Listes en < 10s pour 500 éléments (respecté avec pagination).
- **Sécurité** : Authentification et sessions (implémenté).
- **Ergonomie** : Interface responsive avec Bootstrap.
- **Scalabilité** : Non testé.

---

## Contraintes Techniques
- **Serveur HTTP** : PHP intégré (serveur de dev).
- **Langage** : PHP 8.x.
- **Framework** : Symfony.
- **Base de données** : MariaDB.
- **ORM** : Doctrine.

---

## Architecture Technique
- **Modèle** : MVC avec Symfony.
- **Base de données** : MariaDB avec tables actuelles (`utilisateur`, `projet`, `cable`, `connecteur`, etc.).
- **API** : Non implémenté.

---

## Implémentation Actuelle

### Routes
| Nom                  | URL                                     | Méthode | Description                     |
|----------------------|-----------------------------------------|---------|---------------------------------|
| `utilisateurs_gestion` | `/utilisateurs/gestion`               | GET     | Liste des utilisateurs          |
| `utilisateur_new`    | `/utilisateurs/new`                    | GET/POST| Ajout d’un utilisateur          |
| `utilisateur_edit`   | `/utilisateurs/{id}/edit`              | GET/POST| Modification d’un utilisateur   |
| `utilisateur_supprimer` | `/utilisateurs/{id}`                | POST    | Suppression d’un utilisateur    |
| `mes_projets`        | `/projets/mes-projets`                 | GET     | Liste des projets de l’utilisateur |
| `cable_list`         | `/projet/{projetId}/cables`            | GET     | Liste des câbles                |
| `cable_new`          | `/projet/{projetId}/cables/new`        | GET/POST| Ajout d’un câble                |
| `cable_edit`         | `/projet/{projetId}/cables/{id}/edit`  | GET/POST| Modification d’un câble         |
| `cable_delete`       | `/projet/{projetId}/cables/{id}`       | POST    | Suppression d’un câble          |
| `cable_export_csv`   | `/projet/{projetId}/cables/export`     | GET     | Export CSV des câbles           |
| `connecteur_list`    | `/projet/{projetId}/connecteurs`       | GET     | Liste des connecteurs           |
| `connecteur_new`     | `/projet/{projetId}/connecteurs/new`   | GET/POST| Ajout d’un connecteur           |
| `connecteur_edit`    | `/projet/{projetId}/connecteurs/{id}/edit` | GET/POST| Modification d’un connecteur |
| `connecteur_delete`  | `/projet/{projetId}/connecteurs/{id}`  | POST    | Suppression d’un connecteur     |
| `connecteur_export_csv` | `/projet/{projetId}/connecteurs/export` | GET  | Export CSV des connecteurs      |

### Formulaires
- **UtilisateurType** : `nom`, `email`, `roles`, `plainPassword` (non mappé, requis pour création).
- **CableType** : `nom`, `longueur`, `catalogueProjetCables` (optionnel).
- **CableFilterType** : `nom`, `longueurMin`, `longueurMax`, `catalogueProjetCables`.
- **ConnecteurType** : `nom`, `catalogueProjetConnecteurs` (obligatoire).
- **ConnecteurFilterType** : `nom`, `nombreContacts`, `type`, `catalogueProjetConnecteurs`.

### Templates
- **Utilisateurs** : `gestion.html.twig`, `new.html.twig`, `edit.html.twig`.
- **Projets** : `mes_projets.html.twig`.
- **Câbles** : `list.html.twig`, `new.html.twig`, `edit.html.twig`.
- **Connecteurs** : `list.html.twig`, `new.html.twig`, `edit.html.twig`.
- **Style** : Bootstrap 4, rendu par défaut via `form_widget`.

### Contraintes et Sécurité
- **Rôles** : `ROLE_ADMIN`, `ROLE_USER` (hiérarchie définie).
- **CSRF** : Validé pour suppressions.
- **Validation** : `NotBlank` sur champs requis.
- **Sessions** : Stockées dans `var/sessions`.

---

## Scénarios

### Scénario 1 : Création d’un projet et ajout d’un câblage
- **Implémenté** : Ajout de câbles et connecteurs avec catalogue.
- **Non implémenté** : Conducteurs, borniers, signaux.

### Scénario 2 : Consultation par un lecteur
- **Implémenté** : Liste des câbles et connecteurs avec filtres.
- **Non implémenté** : Signaux.

### Scénario 3 : Modification du catalogue projet
- **Non implémenté**.

### Scénario 4 : Tentative de connexion invalide
- **Implémenté** : Gestion des erreurs d’authentification via `form_login`.

---

## Conclusion

L’implémentation actuelle couvre la gestion des utilisateurs, projets, câbles et connecteurs avec un CRUD complet, des filtres, et un export CSV. Les bases sont posées pour étendre aux borniers, conducteurs, signaux, et équipements. Les prochaines étapes incluent :
- Ajout des entités manquantes (Conducteur, Bornier, Signal, etc.).
- Gestion complète des projets (suppression par admin, rôles multiples).
- Propagation des signaux et rapports avancés.
