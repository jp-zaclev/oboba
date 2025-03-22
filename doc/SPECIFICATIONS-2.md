# Spécifications du logiciel Oboba

*Date : 19 mars 2025*  
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

L’implémentation actuelle se concentre sur la gestion des utilisateurs, projets, câbles et connecteurs dans une application Symfony offline, avec des fonctionnalités de liste paginée, ajout, modification, suppression, filtrage, et exportation en CSV. Depuis le 19 mars 2025, le schéma de la base de données a été consolidé pour inclure toutes les entités nécessaires, avec des règles de suppression précises.

---

## Objectifs

- Permettre la gestion de multiples projets indépendants avec des catalogues spécifiques.
- Offrir une interface utilisateur responsive et intuitive basée sur Bootstrap.
- Gérer les droits d’accès via des rôles (lecteur, concepteur, propriétaire, administrateur).
- Supporter la consultation, la saisie, et l’exportation des données de câblage.
- Garantir que les catalogues projet ne soient supprimés qu’avec leur projet parent, avec un état transitoire acceptable.

---

## Entités et Relations

### Utilisateur
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(100) NOT NULL`
  - `email` : `VARCHAR(180) NOT NULL UNIQUE`
  - `password` : `VARCHAR(255) NOT NULL`
  - `roles` : `LONGTEXT NOT NULL` (JSON)
- **Comportements** : Se connecter, consulter les projets autorisés, gérer les utilisateurs (administrateur uniquement).
- **Implémentation** :
  - Table : `utilisateur`
  - Relations : `OneToMany` avec `ProjetUtilisateur`
- **Statut** : Implémenté avec gestion complète (CRUD, authentification).

### Projet
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `date_heure_creation` : `DATETIME NOT NULL` (remplace `description`)
  - `date_heure_derniere_modification` : `DATETIME NOT NULL` (ajouté)
- **Implémentation** :
  - Table : `projet`
  - Relations : `OneToMany` avec `Cable`, `Connecteur`, `ProjetUtilisateur`, `Signal`, `Bornier`, `Equipement`, `CatalogueProjetCables`, `CatalogueProjetConnecteurs`, `CatalogueProjetBorniers`
- **Statut** : Implémenté (liste des projets par utilisateur disponible, suppression en cascade ajoutée).

### Signal
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(50) NOT NULL UNIQUE`
  - `type` : `ENUM('analogique', 'digital') NOT NULL`
  - `details` : `VARCHAR(255) DEFAULT NULL` (ex. "tension 24V")
  - `id_projet` : `INT NOT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
- **Relations** : Lié à un ou plusieurs `Conducteur` (`ON DELETE SET NULL`).
- **Statut** : Implémenté dans le schéma, mais fonctionnalités CRUD non détaillées.

### Catalogue Modèle des Câbles
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL UNIQUE`
  - `type` : `VARCHAR(50) NOT NULL` (ex. "coaxial")
  - `nombre_conducteurs_max` : `INT NOT NULL`
  - `prix_metre` : `DECIMAL(10,2) NOT NULL`
- **Statut** : Implémenté dans le schéma, mais non utilisé dans l’application actuelle.

### Catalogue Projet des Câbles
- **Table** : `catalogue_projet_cables`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_projet` : `INT NOT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
  - `nom` : `VARCHAR(255) NOT NULL`
  - `nombre_conducteurs` : `INT NOT NULL` (remplace `nombre_conducteurs_max`)
  - `prix_unitaire` : `DECIMAL(10,2) NOT NULL` (remplace `prix_metre`)
- **Relations** : Référencé par `Cable` (`ON DELETE SET NULL`).
- **Règles** : Ne peut être supprimé directement par l’utilisateur, uniquement via la suppression du projet.
- **Statut** : Implémenté dans le schéma et utilisé par `Cable`.

### Câble
- **Table** : `cable`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `longueur` : `INT DEFAULT NULL` (corrigé : nullable)
  - `id_projet` : `INT DEFAULT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
  - `id_catalogue_projet_cable` : `INT DEFAULT NULL` (FK vers `catalogue_projet_cables(id)` avec `ON DELETE SET NULL`)
- **Relations** : Contient un ou plusieurs `Conducteur` (`ON DELETE CASCADE`).
- **Statut** : Implémenté avec CRUD complet, catalogue optionnel.

### Conducteur
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_cable` : `INT NOT NULL` (FK vers `cable(id)` avec `ON DELETE CASCADE`)
  - `attribut` : `VARCHAR(255) NOT NULL` (ex. "couleur: rouge")
  - `id_signal` : `INT DEFAULT NULL` (FK vers `signal(id)` avec `ON DELETE SET NULL`)
  - `id_borne_source` : `INT DEFAULT NULL` (FK vers `borne(id)` avec `ON DELETE SET NULL`)
  - `id_borne_destination` : `INT DEFAULT NULL` (FK vers `borne(id)` avec `ON DELETE SET NULL`)
  - `id_contact_source` : `INT DEFAULT NULL` (FK vers `contact(id)` avec `ON DELETE SET NULL`)
  - `id_contact_destination` : `INT DEFAULT NULL` (FK vers `contact(id)` avec `ON DELETE SET NULL`)
- **Relations** : Transporte un `Signal`, connecte des `Borne` ou `Contact`.
- **Statut** : Implémenté dans le schéma, mais non utilisé dans l’application actuelle.

### Catalogue Modèle des Borniers
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL UNIQUE`
  - `nombre_bornes` : `INT NOT NULL`
  - `caracteristiques` : `VARCHAR(255) DEFAULT NULL`
  - `prix_unitaire` : `DECIMAL(10,2) NOT NULL`
- **Statut** : Implémenté dans le schéma, mais non utilisé dans l’application actuelle.

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
- **Règles** : Ne peut être supprimé directement par l’utilisateur, uniquement via la suppression du projet.
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
- **Statut** : Implémenté dans le schéma, mais non utilisé dans l’application actuelle.

### Catalogue Modèle des Connecteurs
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL UNIQUE`
  - `nombre_contacts` : `INT NOT NULL`
  - `type` : `VARCHAR(50) NOT NULL`
  - `prix_unitaire` : `DECIMAL(10,2) NOT NULL`
- **Statut** : Implémenté dans le schéma, mais non utilisé dans l’application actuelle.

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
- **Règles** : Ne peut être supprimé directement par l’utilisateur, uniquement via la suppression du projet.
- **Statut** : Implémenté dans le schéma et utilisé par `Connecteur`.

### Connecteur
- **Table** : `connecteur`
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `id_projet` : `INT DEFAULT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
  - `id_catalogue_projet_connecteur` : `INT DEFAULT NULL` (FK vers `catalogue_projet_connecteurs(id)` avec `ON DELETE SET NULL`, corrigé : nullable)
- **Relations** : Contient un ou plusieurs `Contact` (`ON DELETE CASCADE`).
- **Statut** : Implémenté avec CRUD complet, catalogue optionnel (corrigé).

### Contact
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `id_connecteur` : `INT NOT NULL` (FK vers `connecteur(id)` avec `ON DELETE CASCADE`)
  - `identifiant` : `VARCHAR(50) NOT NULL` (ex. "A")
  - `type` : `ENUM('emission', 'reception', 'emission_reception') NOT NULL`
- **Statut** : Implémenté dans le schéma, mais non utilisé dans l’application actuelle.

### Equipement
- **Attributs** :
  - `id` : `INT PRIMARY KEY AUTO_INCREMENT`
  - `nom` : `VARCHAR(255) NOT NULL`
  - `reference` : `VARCHAR(50) NOT NULL`
  - `id_projet` : `INT NOT NULL` (FK vers `projet(id)` avec `ON DELETE CASCADE`)
- **Relations** : Peut être connecté à un ou plusieurs `Connecteur`.
- **Statut** : Implémenté dans le schéma, mais non utilisé dans l’application actuelle.

### Relations
- **Implémentées** : 
  - `Utilisateur` → `ProjetUtilisateur`
  - `Projet` → `Cable`, `Connecteur`, `Signal`, `Bornier`, `Equipement`, `CatalogueProjetCables`, `CatalogueProjetConnecteurs`, `CatalogueProjetBorniers` (toutes avec `ON DELETE CASCADE`)
  - `Cable` → `CatalogueProjetCables`, `Connecteur` → `CatalogueProjetConnecteurs`, `Bornier` → `CatalogueProjetBorniers` (toutes avec `ON DELETE SET NULL`)
  - `Cable` → `Conducteur`, `Bornier` → `Borne`, `Connecteur` → `Contact` (toutes avec `ON DELETE CASCADE`)
  - `Conducteur` → `Signal`, `Borne` (source/destination), `Contact` (source/destination) (toutes avec `ON DELETE SET NULL`)
- **Non implémentées dans l’application** : Relations avec `CatalogueModeleCables`, `CatalogueModeleBorniers`, `CatalogueModeleConnecteurs`.

---

## Fonctionnalités Principales

### Gestion des Utilisateurs
- **Spécification** : Comptes avec rôles (administrateur, propriétaire, concepteur, lecteur).
- **Implémentation** :
  - CRUD complet via `UtilisateurController`.
  - Rôles `ROLE_ADMIN` et `ROLE_USER` gérés, authentification par formulaire.
  - Nouvelle table `projet_utilisateur` pour associer utilisateurs et projets avec rôles (`proprietaire`, `concepteur`, `lecteur`).
- **Statut** : Implémenté, enrichi avec `projet_utilisateur`.

### Consultation de la Liste des Projets
- **Spécification** : Liste avec détails et suppression par l’administrateur.
- **Implémentation** :
  - Liste des projets de l’utilisateur connecté via `/projets/mes-projets`.
  - Suppression via `/projets/{id}` (POST) avec cascade sur toutes les entités associées.
- **Statut** : Implémenté, suppression ajoutée.

### Consultation de la Liste des Câbles
- **Spécification** : Liste avec filtres (nom, type, prix max).
- **Implémentation** :
  - Liste paginée (10 par page) via `/projet/{projetId}/cables`.
  - Filtres : `nom`, `longueurMin`, `longueurMax`, `catalogueProjetCables`.
  - Colonnes : `Nom`, `Longueur`, `Catalogue Projet`, `Actions`.
- **Statut** : Implémenté (type et prix max non applicables car non dans le schéma actuel).

### Consultation de la Liste des Signaux
- **Spécification** : Liste avec filtres et détails de transit.
- **Implémentation** : Ajoutée dans le schéma (`signal`), mais pas de route ou interface.
- **Statut** : Non implémenté dans l’application.

### Saisie et Modification des Données (Concepteur)
- **Spécification** : CRUD pour câbles, borniers, connecteurs, signaux.
- **Implémentation** :
  - **Câbles** : Ajout, modification, suppression avec catalogue optionnel.
  - **Connecteurs** : Ajout, modification, suppression avec catalogue optionnel (corrigé : nullable).
  - **Borniers** : Ajouté dans le schéma, mais pas de CRUD.
  - **Signaux** : Ajouté dans le schéma, mais pas de CRUD.
- **Statut** : Partiellement implémenté (câbles et connecteurs complets).

### Gestion des Catalogues
- **Spécification** : Catalogues modèles et spécifiques aux projets.
- **Implémentation** :
  - `CatalogueProjetCables`, `CatalogueProjetConnecteurs`, `CatalogueProjetBorniers` spécifiques aux projets, liés à `projet` avec `ON DELETE CASCADE`.
  - Suppression uniquement via `projet` ; pas de suppression directe par l’utilisateur.
  - Catalogues modèles (`catalogue_modele_xxx`) présents mais non liés à l’application.
  - Pas d’avertissement si un catalogue devient inutilisé après suppression d’un `cable`, `connecteur`, ou `bornier` (normal pour projets nouveaux).
- **Statut** : Implémenté pour catalogues projet, modèles non utilisés.

### Gestion des Erreurs
- **Spécification** : Messages d’erreur pour références invalides, connexions impossibles, etc.
- **Implémentation** :
  - Validation `NotBlank` pour `nom` sur `cable`, `connecteur`, etc.
  - Confirmation CSRF pour suppressions.
  - Gestion des erreurs SQL (ex. contraintes FK corrigées : `id_catalogue_projet_bornier` nullable).
- **Statut** : Partiellement implémenté, erreurs SQL résolues.

### Règles de Propagation des Signaux
- **Spécification** : Propagation avec détection des conflits.
- **Statut** : Non implémenté (schéma prêt avec `conducteur`).

### Fonctionnalités Avancées
- **Spécification** : Export CSV/PDF, rapports, historique.
- **Implémentation** :
  - Export CSV pour câbles et connecteurs via `/projet/{projetId}/cables/export` et `/projet/{projetId}/connecteurs/export`.
- **Statut** : Partiellement implémenté (CSV uniquement).

---

## Spécifications de l’Interface Utilisateur
- **Spécification** : Tableaux triables, formulaires déroulants, messages temporaires.
- **Implémentation** :
  - Tableaux paginés avec Bootstrap.
  - Formulaires simples avec `form_widget` pour `cable`, `connecteur`.
  - Messages flash pour succès/erreurs (ex. suppression réussie).
- **Statut** : Implémenté pour câbles et connecteurs.

---

## Exigences Non Fonctionnelles
- **Performance** : Listes en < 10s pour 500 éléments (respecté avec pagination).
- **Sécurité** : Authentification et sessions (implémenté).
- **Ergonomie** : Interface responsive avec Bootstrap.
- **Scalabilité** : Non testé, mais schéma relationnel robuste.

---

## Contraintes Techniques
- **Serveur HTTP** : PHP intégré (serveur de dev).
- **Langage** : PHP 8.1.2-1ubuntu2.20.
- **Framework** : Symfony (version non précisée).
- **Base de données** : MariaDB 10.6.18-0ubuntu0.22.04.1.
- **ORM** : Doctrine.
- **Encodage** : `utf8mb4` avec `utf8mb4_unicode_ci`.

---

## Architecture Technique
- **Modèle** : MVC avec Symfony.
- **Base de données** : MariaDB avec tables actuelles (`utilisateur`, `projet`, `cable`, `connecteur`, `signal`, `bornier`, `borne`, `conducteur`, `contact`, `equipement`, `catalogue_projet_xxx`, `catalogue_modele_xxx`).
- **API** : Non implémenté.

---

## Implémentation Actuelle

### Routes
| Nom                     | URL                                        | Méthode | Description                        |
|-------------------------|--------------------------------------------|---------|------------------------------------|
| `utilisateurs_gestion`  | `/utilisateurs/gestion`                    | GET     | Liste des utilisateurs             |
| `utilisateur_new`       | `/utilisateurs/new`                        | GET/POST| Ajout d’un utilisateur             |
| `utilisateur_edit`      | `/utilisateurs/{id}/edit`                  | GET/POST| Modification d’un utilisateur      |
| `utilisateur_supprimer` | `/utilisateurs/{id}`                       | POST    | Suppression d’un utilisateur       |
| `mes_projets`           | `/projets/mes-projets`                     | GET     | Liste des projets de l’utilisateur |
| `projet_delete`         | `/projets/{id}`                            | POST    | Suppression d’un projet (cascade)  |
| `cable_list`            | `/projet/{projetId}/cables`                | GET     | Liste des câbles                   |
| `cable_new`             | `/projet/{projetId}/cables/new`            | GET/POST| Ajout d’un câble                   |
| `cable_edit`            | `/projet/{projetId}/cables/{id}/edit`      | GET/POST| Modification d’un câble            |
| `cable_delete`          | `/projet/{projetId}/cables/{id}`           | POST    | Suppression d’un câble             |
| `cable_export_csv`      | `/projet/{projetId}/cables/export`         | GET     | Export CSV des câbles              |
| `connecteur_list`       | `/projet/{projetId}/connecteurs`           | GET     | Liste des connecteurs              |
| `connecteur_new`        | `/projet/{projetId}/connecteurs/new`       | GET/POST| Ajout d’un connecteur              |
| `connecteur_edit`       | `/projet/{projetId}/connecteurs/{id}/edit` | GET/POST| Modification d’un connecteur       |
| `connecteur_delete`     | `/projet/{projetId}/connecteurs/{id}`      | POST    | Suppression d’un connecteur        |
| `connecteur_export_csv` | `/projet/{projetId}/connecteurs/export`    | GET     | Export CSV des connecteurs         |

### Formulaires
- **UtilisateurType** : `nom`, `email`, `roles`, `plainPassword` (non mappé, requis pour création).
- **CableType** : `nom`, `longueur`, `catalogueProjetCables` (optionnel).
- **CableFilterType** : `nom`, `longueurMin`, `longueurMax`, `catalogueProjetCables`.
- **ConnecteurType** : `nom`, `catalogueProjetConnecteurs` (optionnel, corrigé).
- **ConnecteurFilterType** : `nom`, `nombreContacts`, `type`, `catalogueProjetConnecteurs` (partiellement adapté au schéma).

### Templates
- **Utilisateurs** : `gestion.html.twig`, `new.html.twig`, `edit.html.twig`.
- **Projets** : `mes_projets.html.twig`.
- **Câbles** : `list.html.twig`, `new.html.twig`, `edit.html.twig`.
- **Connecteurs** : `list.html.twig`, `new.html.twig`, `edit.html.twig`.
- **Style** : Bootstrap 4, rendu par défaut via `form_widget`.

### Contraintes et Sécurité
- **Rôles** : `ROLE_ADMIN`, `ROLE_USER`, enrichi avec `projet_utilisateur` (`proprietaire`, `concepteur`, `lecteur`).
- **CSRF** : Validé pour suppressions (ex. `projet_delete`, `cable_delete`).
- **Validation** : `NotBlank` sur champs requis (`nom` pour `cable`, `connecteur`, etc.).
- **Sessions** : Stockées dans `var/sessions`.
- **FK Constraints** : Toutes corrigées (ex. `id_catalogue_projet_bornier` nullable).

---

## Scénarios

### Scénario 1 : Création d’un projet et ajout d’un câblage
- **Implémenté** : Ajout de câbles et connecteurs avec catalogue optionnel, projet créé avec `date_heure_creation`.
- **Non implémenté** : Conducteurs, borniers, signaux.

### Scénario 2 : Consultation par un lecteur
- **Implémenté** : Liste des câbles et connecteurs avec filtres via rôle `lecteur`.
- **Non implémenté** : Signaux.

### Scénario 3 : Modification du catalogue projet
- **Implémenté** : Catalogues liés à `cable`, `connecteur`, `bornier` modifiables indirectement.
- **Non implémenté** : CRUD direct des catalogues.

### Scénario 4 : Tentative de connexion invalide
- **Implémenté** : Gestion des erreurs d’authentification via `form_login`.

### Scénario 5 : Suppression d’un projet
- **Implémenté** : Suppression via `projet_delete` entraîne cascade complète (catalogues inclus, état transitoire `NULL` acceptable).

---

## Conclusion

L’implémentation actuelle couvre la gestion des utilisateurs, projets, câbles et connecteurs avec un CRUD complet, des filtres, et un export CSV. Depuis le 19 mars 2025, le schéma inclut toutes les entités (`signal`, `bornier`, `conducteur`, etc.) avec des règles de suppression robustes (ex. catalogues protégés, cascades via `projet`). Les prochaines étapes incluent :
- Ajout des CRUD pour `signal`, `bornier`, `conducteur`, `contact`, `equipement`.
- Intégration des catalogues modèles.
- Propagation des signaux et rapports avancés.  
