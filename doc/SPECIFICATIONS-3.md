# Spécifications du logiciel Oboba

*Date : 27 mars 2025*  
*Projet : Gestion des câbles et connecteurs dans une application Symfony offline*

## Table des matières
1. [Introduction](#introduction)
2. [Objectifs](#objectifs)
3. [Entités et Relations](#entités-et-relations)
   - [Utilisateur](#utilisateur)
   - [Projet](#projet)
   - [WireSignal](#wiresignal)
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
   - [Consultation de la Liste des Connecteurs](#consultation-de-la-liste-des-connecteurs)
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

L’implémentation actuelle se concentre sur la gestion des utilisateurs, projets, câbles, connecteurs, et catalogues modèles dans une application Symfony offline, avec des fonctionnalités de liste paginée, ajout, modification, suppression, filtrage, et exportation en CSV. Depuis le 23 mars 2025, le schéma de la base de données est consolidé, les catalogues modèles disposent de CRUD complets, et les listes de câbles et connecteurs ont été améliorées avec des contrôles d’accès robustes et une navigation optimisée. Au 27 mars 2025, des ajustements ont été apportés aux entités `CatalogueModeleCables` et `CatalogueProjetCables` pour refléter un nombre exact de conducteurs.

---

## Objectifs

- Permettre la gestion de multiples projets indépendants avec des catalogues spécifiques.
- Offrir une interface utilisateur responsive et intuitive basée sur Bootstrap.
- Gérer les droits d’accès via des rôles (lecteur, concepteur, propriétaire, administrateur).
- Supporter la consultation, la saisie, et l’exportation des données de câblage.
- Garantir que les catalogues projet ne soient supprimés qu’avec leur projet parent, avec un état transitoire acceptable.
- Fournir des interfaces CRUD pour les catalogues modèles (câbles, connecteurs, borniers) accessibles aux administrateurs.
- Permettre un accès centralisé aux données et actions d’un projet via une page dédiée.
- Améliorer la navigation avec des boutons de retour vers la page projet depuis les listes.

---

## Entités et Relations

### Utilisateur
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(100) NOT NULL`
  - `email` : `VARCHAR(180) NOT NULL UNIQUE`
  - `password` : `VARCHAR(255) NOT NULL`
  - `roles` : `LONGTEXT NOT NULL` (JSON)
- **Comportements** : Se connecter, consulter les projets autorisés, gérer les utilisateurs et catalogues (administrateur uniquement).
- **Implémentation** :
  - Table : `utilisateur`
  - Relations : `OneToMany` avec `ProjetUtilisateur`
- **Statut** : Implémenté avec gestion complète (CRUD, authentification).

### Projet
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `date_heure_creation` : `DATETIME NOT NULL`
  - `date_heure_derniere_modification` : `DATETIME NOT NULL`
- **Implémentation** :
  - Table : `projet`
  - Relations : `OneToMany` avec `Cable`, `Connecteur`, `ProjetUtilisateur`, `WireSignal`, `Bornier`, `Equipement`, `CatalogueProjetCables`, `CatalogueProjetConnecteurs`, `CatalogueProjetBorniers`
- **Statut** : Implémenté (liste des projets par utilisateur, suppression en cascade, page spécifique avec navigation vers câbles et connecteurs).

### WireSignal
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(50) NOT NULL UNIQUE`
  - `type` : `ENUM('analogique', 'digital') NOT NULL`
  - `details` : `VARCHAR(255) DEFAULT NULL` (ex. "tension 24V")
  - `id_projet` : `INT NOT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
- **Relations** : Lié à un ou plusieurs `Conducteur` (`ON DELETE SET NULL`).
- **Note** : Renommé de `Signal` à `WireSignal` pour éviter les mots réservés SQL.
- **Statut** : Implémenté dans le schéma, mais CRUD non implémenté.

### Catalogue Modèle des Câbles
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL UNIQUE`
  - `type` : `VARCHAR(50) NOT NULL` (ex. "coaxial")
  - `nombre_conducteurs` : `INT NOT NULL` (anciennement `nombre_conducteurs_max`)
  - `prix_unitaire` : `DECIMAL(10,2) NOT NULL`
- **Note** : Renommé au 27 mars 2025 pour refléter le nombre exact de conducteurs plutôt qu’un maximum.
- **Statut** : Implémenté avec CRUD complet pour les administrateurs.

### Catalogue Projet des Câbles
- **Table** : `catalogue_projet_cables`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_projet` : `INT NOT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
  - `nom` : `VARCHAR(255) NOT NULL`
  - `nombre_conducteurs` : `INT NOT NULL` (anciennement `nombre_conducteurs_max`)
  - `prix_unitaire` : `DECIMAL(10,2) NOT NULL`
- **Relations** : Référencé par `Cable` (`ON DELETE SET NULL`).
- **Règles** : Ne peut être supprimé directement, uniquement via le projet.
- **Note** : Renommé au 27 mars 2025 pour refléter le nombre exact de conducteurs plutôt qu’un maximum.
- **Statut** : Implémenté dans le schéma et utilisé par `Cable`.

### Câble
- **Table** : `cable`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `longueur` : `INT DEFAULT NULL`
  - `id_projet` : `INT DEFAULT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
  - `id_catalogue_projet_cable` : `INT DEFAULT NULL` (FK vers `catalogue_projet_cables(id)` avec `ON DELETE SET NULL`)
- **Relations** : Contient un ou plusieurs `Conducteur` (`ON DELETE CASCADE`).
- **Statut** : Implémenté avec CRUD complet, catalogue optionnel.

### Conducteur
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_cable` : `INT NOT NULL` (FK vers `cable(id)` avec `ON DELETE CASCADE`)
  - `attribut` : `VARCHAR(255) NOT NULL` (ex. "couleur: rouge")
  - `id_signal` : `INT DEFAULT NULL` (FK vers `wiresignal(id)` avec `ON DELETE SET NULL`)
  - `id_borne_source` : `INT DEFAULT NULL` (FK vers `borne(id)` avec `ON DELETE SET NULL`)
  - `id_borne_destination` : `INT DEFAULT NULL` (FK vers `borne(id)` avec `ON DELETE SET NULL`)
  - `id_contact_source` : `INT DEFAULT NULL` (FK vers `contact(id)` avec `ON DELETE SET NULL`)
  - `id_contact_destination` : `INT DEFAULT NULL` (FK vers `contact(id)` avec `ON DELETE SET NULL`)
- **Relations** : Transporte un `WireSignal`, connecte des `Borne` ou `Contact`.
- **Statut** : Implémenté dans le schéma, mais non utilisé.

### Catalogue Modèle des Borniers
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL UNIQUE`
  - `nombre_bornes` : `INT NOT NULL`
  - `caracteristiques` : `VARCHAR(255) DEFAULT NULL`
  - `prix_unitaire` : `DECIMAL(10,2) NOT NULL`
- **Statut** : Implémenté avec CRUD complet pour les administrateurs.

### Catalogue Projet des Borniers
- **Table** : `catalogue_projet_borniers`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_projet` : `INT NOT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
  - `nom` : `VARCHAR(255) NOT NULL`
  - `nombre_bornes` : `INT NOT NULL`
  - `caracteristiques` : `VARCHAR(255) DEFAULT NULL`
  - `prix_unitaire` : `DECIMAL(10,2) NOT NULL`
- **Relations** : Référencé par `Bornier` (`ON DELETE SET NULL`).
- **Règles** : Ne peut être supprimé directement, uniquement via le projet.
- **Statut** : Implémenté dans le schéma et utilisé par `Bornier`.

### Bornier
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `id_catalogue_projet_bornier` : `INT DEFAULT NULL` (FK vers `catalogue_projet_borniers(id)` avec `ON DELETE SET NULL`)
  - `id_projet` : `INT NOT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
  - `localisation` : `VARCHAR(255) NOT NULL`
- **Relations** : Contient une ou plusieurs `Borne` (`ON DELETE CASCADE`).
- **Contrainte** : Unicité sur `nom` et `id_projet`.
- **Statut** : Implémenté dans le schéma, mais fonctionnalités CRUD non détaillées.

### Borne
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_bornier` : `INT NOT NULL` (FK vers `bornier(id)` avec `ON DELETE CASCADE`)
  - `identification` : `VARCHAR(50) NOT NULL` (ex. "1")
- **Statut** : Implémenté dans le schéma, mais non utilisé.

### Catalogue Modèle des Connecteurs
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL UNIQUE`
  - `nombre_contacts` : `INT NOT NULL`
  - `type` : `VARCHAR(50) NOT NULL`
  - `prix_unitaire` : `DECIMAL(10,2) NOT NULL`
- **Statut** : Implémenté avec CRUD complet pour les administrateurs.

### Catalogue Projet des Connecteurs
- **Table** : `catalogue_projet_connecteurs`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_projet` : `INT NOT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
  - `nom` : `VARCHAR(255) NOT NULL`
  - `nombre_contacts` : `INT NOT NULL`
  - `type` : `VARCHAR(50) NOT NULL`
  - `prix_unitaire` : `DECIMAL(10,2) NOT NULL`
- **Relations** : Référencé par `Connecteur` (`ON DELETE SET NULL`).
- **Règles** : Ne peut être supprimé directement, uniquement via le projet.
- **Statut** : Implémenté dans le schéma et utilisé par `Connecteur`.

### Connecteur
- **Table** : `connecteur`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `id_projet` : `INT DEFAULT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
  - `id_catalogue_projet_connecteur` : `INT DEFAULT NULL` (FK vers `catalogue_projet_connecteurs(id)` avec `ON DELETE SET NULL`)
- **Relations** : Contient un ou plusieurs `Contact` (`ON DELETE CASCADE`).
- **Statut** : Implémenté avec CRUD complet, catalogue optionnel.

### Contact
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_connecteur` : `INT NOT NULL` (FK vers `connecteur(id)` avec `ON DELETE CASCADE`)
  - `identifiant` : `VARCHAR(50) NOT NULL` (ex. "A")
  - `type` : `ENUM('emission', 'reception', 'emission_reception') NOT NULL`
- **Statut** : Implémenté dans le schéma, mais non utilisé.

### Equipement
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `reference` : `VARCHAR(50) NOT NULL`
  - `id_projet` : `INT NOT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
- **Relations** : Peut être connecté à un ou plusieurs `Connecteur`.
- **Statut** : Implémenté dans le schéma, mais non utilisé.

### Relations
- **Implémentées** : 
  - `Utilisateur` → `ProjetUtilisateur`
  - `Projet` → `Cable`, `Connecteur`, `WireSignal`, `Bornier`, `Equipement`, `CatalogueProjetCables`, `CatalogueProjetConnecteurs`, `CatalogueProjetBorniers` (toutes avec `ON DELETE CASCADE`)
  - `Cable` → `CatalogueProjetCables`, `Connecteur` → `CatalogueProjetConnecteurs`, `Bornier` → `CatalogueProjetBorniers` (toutes avec `ON DELETE SET NULL`)
  - `Cable` → `Conducteur`, `Bornier` → `Borne`, `Connecteur` → `Contact` (toutes avec `ON DELETE CASCADE`)
  - `Conducteur` → `WireSignal`, `Borne` (source/destination), `Contact` (source/destination) (toutes avec `ON DELETE SET NULL`)
- **Non implémentées dans l’application** : Relations directes avec `CatalogueModeleCables`, `CatalogueModeleBorniers`, `CatalogueModeleConnecteurs` (utilisées indépendamment via CRUD).

---

## Fonctionnalités Principales

### Gestion des Utilisateurs
- **Spécification** : Comptes avec rôles (administrateur, propriétaire, concepteur, lecteur).
- **Implémentation** :
  - CRUD complet via `UtilisateurController`.
  - Rôles `ROLE_ADMIN` et `ROLE_USER` gérés, authentification par formulaire.
  - Table `projet_utilisateur` pour associer utilisateurs et projets avec rôles (`proprietaire`, `concepteur`, `lecteur`).
- **Statut** : Implémenté, enrichi avec `projet_utilisateur`.

### Consultation de la Liste des Projets
- **Spécification** : Liste avec détails et suppression par l’administrateur, accès à une page spécifique par projet avec actions associées (câbles, connecteurs, borniers, signaux, catalogues projet).
- **Implémentation** :
  - Liste des projets de l’utilisateur connecté via `/projets/mes-projets`, chaque nom est un lien vers `/projets/{id}` (route `projet_show`).
  - Page spécifique par projet avec informations (`nom`, `date_heure_creation`, `date_heure_derniere_modification`) et boutons pour :
    - Liste des câbles (`/projet/{id}/cables`).
    - Liste des connecteurs (`/projet/{id}/connecteurs`).
    - Liste des borniers (`/projet/{id}/borniers`, non implémenté).
    - Liste des signaux (`/projet/{id}/signaux`, non implémenté, commenté).
    - Catalogue projet des câbles (`/projet/{id}/catalogue/cables`, non implémenté).
    - Catalogue projet des connecteurs (`/projet/{id}/catalogue/connecteurs`, non implémenté).
    - Catalogue projet des borniers (`/projet/{id}/catalogue/borniers`, non implémenté).
  - Suppression via `/projets/{id}` (POST) avec cascade sur toutes les entités associées.
- **Statut** : Implémenté pour la liste et la suppression, page spécifique avec actions pour câbles et connecteurs, autres en cours.

### Consultation de la Liste des Câbles
- **Spécification** : Liste avec filtres (nom, type, prix max).
- **Implémentation** :
  - Liste paginée (10 par page) via `/projet/{projetId}/cables`.
  - Filtres : `nom`, `longueurMin`, `longueurMax`, `catalogueProjetCables`.
  - Colonnes : `Nom`, `Longueur`, `Catalogue Projet`, `Actions`.
  - Boutons : "Retour à <nom du projet>", "Ajouter un câble" (si autorisé), "Exporter en CSV".
  - Correction : Contrôle d’accès via DQL explicite dans `CableController` pour éviter les erreurs de lazy loading.
- **Statut** : Implémenté avec navigation améliorée (bouton "Retour à <nom du projet>" ajouté).

### Consultation de la Liste des Connecteurs
- **Spécification** : Liste avec filtres (nom, type, nombre de contacts).
- **Implémentation** :
  - Liste paginée (10 par page) via `/projet/{projetId}/connecteurs`.
  - Filtres : `nom`, `nombreContacts`, `type`, `catalogueProjetConnecteurs`.
  - Colonnes : `Nom`, `Catalogue Projet`, `Nombre de contacts`, `Type`, `Actions`.
  - Boutons : "Retour à <nom du projet>", "Ajouter un connecteur" (si autorisé), "Exporter en CSV".
  - Correction : Contrôle d’accès via DQL explicite dans `ConnecteurController` et ajustement des filtres dans `ConnecteurFilterType`.
- **Statut** : Implémenté avec navigation améliorée (bouton "Retour à <nom du projet>" ajouté).

### Consultation de la Liste des Signaux
- **Spécification** : Liste avec filtres et détails de transit.
- **Implémentation** : Ajoutée dans le schéma (`wiresignal`), mais pas de route ou interface.
- **Statut** : Non implémenté dans l’application.

### Saisie et Modification des Données (Concepteur)
- **Spécification** : CRUD pour câbles, borniers, connecteurs, signaux.
- **Implémentation** :
  - **Câbles** : Ajout, modification, suppression avec catalogue optionnel via `CableController`.
  - **Connecteurs** : Ajout, modification, suppression avec catalogue optionnel via `ConnecteurController`.
  - **Borniers** : Ajouté dans le schéma, mais pas de CRUD.
  - **Signaux** : Ajouté dans le schéma (`WireSignal`), mais pas de CRUD.
- **Statut** : Partiellement implémenté (câbles et connecteurs complets).

### Gestion des Catalogues
- **Spécification** : Catalogues modèles et spécifiques aux projets avec CRUD pour les modèles.
- **Implémentation** :
  - **Catalogues Projet** :
    - `CatalogueProjetCables`, `CatalogueProjetConnecteurs`, `CatalogueProjetBorniers` liés à `projet` avec `ON DELETE CASCADE`.
    - Suppression uniquement via `projet` ; pas de suppression directe par l’utilisateur.
    - Boutons d’accès prévus dans la page projet (`/projet/{id}/catalogue/...`), non implémentés.
  - **Catalogues Modèles** :
    - `CatalogueModeleCables`, `CatalogueModeleConnecteurs`, `CatalogueModeleBorniers` : CRUD complet sous `/admin/catalogue/modele/...`.
    - Accessibles via la navbar pour les administrateurs.
- **Statut** : Implémenté pour catalogues projet et modèles.

### Gestion des Erreurs
- **Spécification** : Messages d’erreur pour références invalides, connexions impossibles, etc.
- **Implémentation** :
  - Validation `NotBlank` pour `nom` sur `cable`, `connecteur`, catalogues modèles.
  - Confirmation CSRF pour suppressions.
  - Correction d’accès : Remplacement de `$projetUtilisateurs->exists()` par DQL explicite dans `CableController` et `ConnecteurController`.
  - Correction des filtres : Ajustement de `idProjet` à `projet` dans `CableFilterType` et `ConnecteurFilterType`.
- **Statut** : Implémenté, erreurs d’accès et de filtres résolues.

### Règles de Propagation des Signaux
- **Spécification** : Propagation avec détection des conflits.
- **Implémentation** : Placeholder prévu dans la page projet (commenté).
- **Statut** : Non implémenté (schéma prêt avec `conducteur` et `wiresignal`).

### Fonctionnalités Avancées
- **Spécification** : Export CSV/PDF, rapports, historique.
- **Implémentation** :
  - Export CSV pour câbles et connecteurs via `/projet/{projetId}/cables/export` et `/projet/{projetId}/connecteurs/export`.
  - Placeholders prévus dans la page projet pour propagation, export/import, rapports.
- **Statut** : Partiellement implémenté (CSV uniquement).

---

## Spécifications de l’Interface Utilisateur
- **Spécification** : Tableaux triables, formulaires déroulants, messages temporaires, navigation intuitive.
- **Implémentation** :
  - Tableaux paginés avec `KnpPaginatorBundle` pour câbles, connecteurs, et catalogues modèles.
  - Filtres dynamiques pour câbles (`nom`, `longueurMin/Max`, `catalogueProjetCables`) et connecteurs (`nom`, `nombreContacts`, `type`, `catalogueProjetConnecteurs`).
  - Bouton "Retour à <nom du projet>" ajouté dans `cable/list.html.twig` et `connecteur/list.html.twig` pour revenir à `/projets/{id}`.
  - Messages flash pour succès/erreurs.
  - Navbar avec liens vers les catalogues modèles pour les administrateurs.
- **Statut** : Implémenté avec navigation améliorée.

---

## Exigences Non Fonctionnelles
- **Performance** : Listes en < 10s pour 500 éléments (respecté avec pagination).
- **Sécurité** : Authentification et sessions (implémenté), restriction `ROLE_ADMIN` pour CRUD des catalogues modèles, contrôle d’accès via DQL.
- **Ergonomie** : Interface responsive avec Bootstrap, navigation optimisée.
- **Scalabilité** : Non testé, mais schéma relationnel robuste.

---

## Contraintes Techniques
- **Serveur HTTP** : PHP intégré (serveur de dev).
- **Langage** : PHP 8.1.2-1ubuntu2.20.
- **Framework** : Symfony (version non précisée).
- **Base de données** : MariaDB 10.6.18-0ubuntu0.22.04.1.
- **ORM** : Doctrine.
- **Encodage** : `utf8mb4` avec `utf8mb4_unicode_ci`.
- **Dépendances** : `KnpPaginatorBundle` pour pagination.

---

## Architecture Technique
- **Modèle** : MVC avec Symfony.
- **Base de données** : MariaDB avec tables actuelles (`utilisateur`, `projet`, `cable`, `connecteur`, etc.).
- **Contrôle d’accès** : Factorisé dans `BaseController` avec `checkProjectAccess`.

---

## Implémentation Actuelle

### Routes
| Nom                              | URL                                               | Méthode | Description                        |
|----------------------------------|---------------------------------------------------|---------|------------------------------------|
| `projet_show`                    | `/projets/{id}`                                   | GET     | Page spécifique d’un projet        |
| `cable_list`                     | `/projet/{projetId}/cables`                       | GET     | Liste des câbles                   |
| `cable_new`                      | `/projet/{projetId}/cables/new`                   | GET/POST| Ajout d’un câble                   |
| `cable_edit`                     | `/projet/{projetId}/cables/{id}/edit`             | GET/POST| Modification d’un câble            |
| `cable_delete`                   | `/projet/{projetId}/cables/{id}`                  | POST    | Suppression d’un câble             |
| `cable_export_csv`               | `/projet/{projetId}/cables/export`                | GET     | Export CSV des câbles              |
| `connecteur_list`                | `/projet/{projetId}/connecteurs`                  | GET     | Liste des connecteurs              |
| `connecteur_new`                 | `/projet/{projetId}/connecteurs/new`              | GET/POST| Ajout d’un connecteur              |
| `connecteur_edit`                | `/projet/{projetId}/connecteurs/{id}/edit`        | GET/POST| Modification d’un connecteur       |
| `connecteur_delete`              | `/projet/{projetId}/connecteurs/{id}`             | POST    | Suppression d’un connecteur        |
| `connecteur_export_csv`          | `/projet/{projetId}/connecteurs/export`           | GET     | Export CSV des connecteurs         |
| *(Autres routes inchangées)*     |                                                   |         |                                    |

### Formulaires
- **CableFilterType** : `nom`, `longueurMin`, `longueurMax`, `catalogueProjetCables` (corrigé : `projet` au lieu de `idProjet`).
- **ConnecteurFilterType** : `nom`, `nombreContacts`, `type`, `catalogueProjetConnecteurs` (corrigé : `projet` avec paramètre `:projet`).
- *(Autres formulaires inchangés)*

### Templates
- **Câbles** : 
  - `list.html.twig` : Liste paginée avec filtres, boutons "Retour à <nom du projet>", "Ajouter un câble", "Exporter en CSV".
- **Connecteurs** : 
  - `list.html.twig` : Liste paginée avec filtres, boutons "Retour à <nom du projet>", "Ajouter un connecteur", "Exporter en CSV".
- *(Autres templates inchangés)*

### Contraintes et Sécurité
- **Contrôle d’accès** : Factorisé dans `BaseController::checkProjectAccess` avec DQL explicite pour éviter les erreurs de lazy loading.
- **Mise à jour au 27 mars 2025** : Renommage de `nombreConducteursMax` en `nombreConducteurs` dans `CatalogueModeleCables` et `CatalogueProjetCables`, avec migrations appliquées (`ALTER TABLE` pour les tables correspondantes).
- *(Autres contraintes inchangées)*

---

## Scénarios

### Scénario 2 : Consultation par un lecteur
- **Implémenté** : Liste des câbles et connecteurs avec filtres et bouton "Retour à <nom du projet>" via rôle `lecteur`.

### Scénario 7 : Consultation et gestion d’un projet spécifique
- **Implémenté** : Liste des câbles et connecteurs avec navigation retour vers `/projets/{id}` via bouton "Retour à <nom du projet>".
- *(Autres scénarios inchangés)*

---

## Conclusion

L’implémentation au 27 mars 2025 couvre la gestion des utilisateurs, projets, câbles, connecteurs, et catalogues modèles avec des CRUD complets, des filtres, et une interface améliorée. Les corrections et mises à jour incluent :
- Contrôle d’accès robuste via DQL dans `CableController` et `ConnecteurController`.
- Ajustement des filtres dans `CableFilterType` et `ConnecteurFilterType`.
- Ajout de boutons "Retour à <nom du projet>" dans les listes de câbles et connecteurs.
- Renommage de `nombreConducteursMax` en `nombreConducteurs` dans `CatalogueModeleCables` et `CatalogueProjetCables` pour refléter un nombre exact de conducteurs, avec migrations appliquées.

Les prochaines étapes incluent :
- Finalisation de la page projet avec toutes les listes et placeholders.
- Ajout des CRUD pour `WireSignal`, `Bornier`, `Conducteur`, `Contact`, `Equipement`.
- Intégration des catalogues projet avec CRUD direct (optionnel).
- Mise à jour de l’IHM pour refléter le renommage de `nombreConducteurs` dans les formulaires et templates.
- Développement des fonctionnalités avancées (propagation des signaux, export/import, rapports).
