# Spécifications du projet Oboba

Oboba est une application de gestion de projets techniques, probablement liée à des installations électriques ou électroniques, permettant de gérer des projets, leurs équipements, câbles, connecteurs, borniers, signaux, et utilisateurs associés. Ce document décrit le schéma de la base de données, les règles métier, et les comportements attendus, basés sur l’état actuel au 19 mars 2025.

---

## Base de données

### Configuration
- **SGBD** : MariaDB 10.6.18 (sur Ubuntu 22.04).
- **Outil** : phpMyAdmin 5.1.1deb5ubuntu1.
- **Encodage** : `utf8mb4` avec collation `utf8mb4_unicode_ci`.
- **Moteur** : InnoDB pour toutes les tables.

### Schéma (basé sur l’export du 19/03/2025 à 19:09)

#### Table `projet`
- **Description** : Représente un projet dans Oboba.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `nom` : VARCHAR(255) NOT NULL.
  - `date_heure_creation` : DATETIME NOT NULL.
  - `date_heure_derniere_modification` : DATETIME NOT NULL.

#### Table `utilisateur`
- **Description** : Utilisateurs ayant accès aux projets.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `nom` : VARCHAR(100) NOT NULL.
  - `email` : VARCHAR(180) NOT NULL, unique.
  - `password` : VARCHAR(255) NOT NULL.
  - `roles` : LONGTEXT NOT NULL (JSON).

#### Table `projet_utilisateur`
- **Description** : Relation entre projets et utilisateurs avec rôles.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `projet_id` : INT(11) NOT NULL, FK vers `projet(id)` (`ON DELETE CASCADE`).
  - `utilisateur_id` : INT(11) NOT NULL, FK vers `utilisateur(id)`.
  - `role` : ENUM('proprietaire', 'concepteur', 'lecteur') NOT NULL.

#### Table `signal`
- **Description** : Signaux associés à un projet (ex. température, alarme).
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_projet` : INT(11) NOT NULL, FK vers `projet(id)` (`ON DELETE CASCADE`).
  - `nom` : VARCHAR(50) NOT NULL, unique.
  - `type` : ENUM('analogique', 'digital') NOT NULL.
  - `details` : VARCHAR(255) DEFAULT NULL.

#### Table `connecteur`
- **Description** : Connecteurs d’un projet.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_catalogue_projet_connecteur` : INT(11) DEFAULT NULL, FK vers `catalogue_projet_connecteurs(id)` (`ON DELETE SET NULL`).
  - `id_projet` : INT(11) DEFAULT NULL, FK vers `projet(id)` (`ON DELETE CASCADE`).
  - `nom` : VARCHAR(255) NOT NULL.

#### Table `contact`
- **Description** : Contacts d’un connecteur.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_connecteur` : INT(11) NOT NULL, FK vers `connecteur(id)` (`ON DELETE CASCADE`).
  - `identifiant` : VARCHAR(50) NOT NULL.
  - `type` : ENUM('emission', 'reception', 'emission_reception') NOT NULL.

#### Table `cable`
- **Description** : Câbles d’un projet.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_catalogue_projet_cable` : INT(11) DEFAULT NULL, FK vers `catalogue_projet_cables(id)` (`ON DELETE SET NULL`).
  - `id_projet` : INT(11) DEFAULT NULL, FK vers `projet(id)` (`ON DELETE CASCADE`).
  - `nom` : VARCHAR(255) NOT NULL.
  - `longueur` : INT(11) DEFAULT NULL.

#### Table `conducteur`
- **Description** : Conducteurs dans un câble, reliant signaux, bornes, ou contacts.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_cable` : INT(11) NOT NULL, FK vers `cable(id)` (`ON DELETE CASCADE`).
  - `id_signal` : INT(11) DEFAULT NULL, FK vers `signal(id)` (`ON DELETE SET NULL`).
  - `id_borne_source` : INT(11) DEFAULT NULL, FK vers `borne(id)` (`ON DELETE SET NULL`).
  - `id_borne_destination` : INT(11) DEFAULT NULL, FK vers `borne(id)` (`ON DELETE SET NULL`).
  - `id_contact_source` : INT(11) DEFAULT NULL, FK vers `contact(id)` (`ON DELETE SET NULL`).
  - `id_contact_destination` : INT(11) DEFAULT NULL, FK vers `contact(id)` (`ON DELETE SET NULL`).
  - `attribut` : VARCHAR(255) NOT NULL.

#### Table `bornier`
- **Description** : Borniers d’un projet.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_catalogue_projet_bornier` : INT(11) DEFAULT NULL, FK vers `catalogue_projet_borniers(id)` (`ON DELETE SET NULL`).
  - `id_projet` : INT(11) NOT NULL, FK vers `projet(id)` (`ON DELETE CASCADE`).
  - `nom` : VARCHAR(255) NOT NULL.
  - `localisation` : VARCHAR(255) NOT NULL.
- **Contrainte** : `nom` et `id_projet` forment une clé unique.

#### Table `borne`
- **Description** : Bornes d’un bornier.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_bornier` : INT(11) NOT NULL, FK vers `bornier(id)` (`ON DELETE CASCADE`).
  - `identification` : VARCHAR(50) NOT NULL.

#### Table `equipement`
- **Description** : Équipements d’un projet.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_projet` : INT(11) NOT NULL, FK vers `projet(id)` (`ON DELETE CASCADE`).
  - `nom` : VARCHAR(255) NOT NULL.
  - `reference` : VARCHAR(50) NOT NULL.

#### Table `catalogue_projet_borniers`
- **Description** : Catalogue des borniers spécifiques à un projet.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_projet` : INT(11) NOT NULL, FK vers `projet(id)` (`ON DELETE CASCADE`).
  - `nom` : VARCHAR(255) NOT NULL.
  - `nombre_bornes` : INT(11) NOT NULL.
  - `prix_unitaire` : DECIMAL(10,2) NOT NULL.
  - `caracteristiques` : VARCHAR(255) DEFAULT NULL.

#### Table `catalogue_projet_cables`
- **Description** : Catalogue des câbles spécifiques à un projet.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_projet` : INT(11) NOT NULL, FK vers `projet(id)` (`ON DELETE CASCADE`).
  - `nom` : VARCHAR(255) NOT NULL.
  - `nombre_conducteurs` : INT(11) NOT NULL.
  - `prix_unitaire` : DECIMAL(10,2) NOT NULL.

#### Table `catalogue_projet_connecteurs`
- **Description** : Catalogue des connecteurs spécifiques à un projet.
- **Colonnes** :
  - `id` : INT(11) AUTO_INCREMENT, clé primaire.
  - `id_projet` : INT(11) NOT NULL, FK vers `projet(id)` (`ON DELETE CASCADE`).
  - `nom` : VARCHAR(255) NOT NULL.
  - `nombre_contacts` : INT(11) NOT NULL.
  - `type` : VARCHAR(50) NOT NULL.
  - `prix_unitaire` : DECIMAL(10,2) NOT NULL.

#### Tables de catalogues modèles (non liées à un projet)
- **`catalogue_modele_borniers`** :
  - `id`, `nom` (unique), `nombre_bornes`, `prix_unitaire`, `caracteristiques`.
- **`catalogue_modele_cables`** :
  - `id`, `nom` (unique), `nombre_conducteurs_max`, `prix_metre`, `type`.
- **`catalogue_modele_connecteurs`** :
  - `id`, `nom` (unique), `nombre_contacts`, `type`, `prix_unitaire`.

---

## Règles métier

### Suppression
- **Projet** :
  - La suppression d’un `projet` entraîne la suppression en cascade de toutes ses entités associées : `signal`, `connecteur` (et ses `contact`), `cable` (et ses `conducteur`), `bornier` (et ses `borne`), `equipement`, `projet_utilisateur`, et les catalogues (`catalogue_projet_borniers`, `catalogue_projet_cables`, `catalogue_projet_connecteurs`).
  - L’ordre de suppression n’est pas critique ; les états transitoires (ex. `id_catalogue_xxx` à `NULL`) sont acceptables car le projet est en voie de destruction.
- **Catalogues projet** :
  - Les catalogues (`catalogue_projet_borniers`, `catalogue_projet_cables`, `catalogue_projet_connecteurs`) ne peuvent être supprimés directement par l’utilisateur. Leur suppression est uniquement déclenchée par la suppression d’un `projet`.
  - Si un catalogue est supprimé (via cascade), les références dans `bornier`, `cable`, `connecteur` passent à `NULL` (`ON DELETE SET NULL`).
- **Éléments individuels** :
  - Supprimer un `bornier`, `cable`, ou `connecteur` ne supprime pas son catalogue associé (dissociation via `SET NULL`).
  anomaly est gérée dans le code avec un avertissement à l’utilisateur si le catalogue devient inutilisé.
- **Conducteur** :
  - Supprimé uniquement si son `cable` est supprimé (`ON DELETE CASCADE`).
  - Si `signal`, `borne`, ou `contact` est supprimé, les références dans `conducteur` passent à `NULL` (`ON DELETE SET NULL`).
- **Borne et Contact** :
  - Supprimés uniquement si leur `bornier` ou `connecteur` est supprimé (`ON DELETE CASCADE`).

### Gestion dans le code
- **Suppression d’un projet** :
  - Gérée par `ProjetController::delete` avec une simple suppression de l’entité `Projet`. Les cascades de la base de données font le reste.
- **Protection des catalogues** :
  - Aucune route ou action ne doit permettre la suppression directe des `catalogue_projet_xxx` (à vérifier dans les contrôleurs).
- **Avertissements** :
  - Lors de la suppression d’un `bornier`, `cable`, ou `connecteur`, vérifier si leur catalogue devient inutilisé (plus lié à aucun élément) et afficher un message d’avertissement à l’utilisateur.

---

## Contrôleurs (hypothétiques)

- **`ProjetController`** :
  - `delete` : Supprime un projet et déclenche toutes les cascades.
- **`BornierController`, `CableController`, `ConnecteurController`** :
  - `delete` : Supprime l’élément et vérifie l’utilisation du catalogue associé pour un éventuel avertissement.
- **`CatalogueProjetXXXController`** :
  - Ne doit pas avoir de méthode `delete` pour empêcher la suppression directe.

---

## Notes techniques

- **Mots-clés réservés** :
  - `signal` est un mot-clé SQL dans MariaDB. Utiliser des backticks (`` `signal` ``) dans les requêtes pour éviter les erreurs de syntaxe (#1064).
- **Tests recommandés** :
  - Supprimer un `projet` avec des entités associées et vérifier que tout est supprimé sans erreur.
  - Supprimer un `bornier` et confirmer que son catalogue reste intact.

---

## Évolutions possibles
- Ajouter une gestion des orphelins dans `ProjetController` (avertissement sur les éléments dissociés temporairement, bien que non nécessaire actuellement).
- Vérifier et documenter les relations avec `catalogue_modele_xxx` si elles sont utilisées dans l’application.

*Dernière mise à jour : 19 mars 2025*
