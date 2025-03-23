# Manuel Utilisateur du Logiciel Oboba

*Date : 23 mars 2025*  
*Version : En cours de développement*  
*Logiciel : Gestion des câbles et connecteurs dans une application Symfony offline*

## Table des matières
1. [Introduction](#introduction)
2. [Connexion au Logiciel](#connexion-au-logiciel)
3. [Vue d’Ensemble de l’Interface](#vue-densemble-de-linterface)
4. [Gestion des Projets](#gestion-des-projets)
   - [Consulter vos projets](#consulter-vos-projets)
   - [Accéder à un projet spécifique](#accéder-à-un-projet-spécifique)
5. [Gestion des Câbles](#gestion-des-câbles)
   - [Voir la liste des câbles](#voir-la-liste-des-câbles)
   - [Ajouter un câble](#ajouter-un-câble)
   - [Modifier un câble](#modifier-un-câble)
   - [Supprimer un câble](#supprimer-un-câble)
   - [Exporter les câbles en CSV](#exporter-les-câbles-en-csv)
6. [Gestion des Connecteurs](#gestion-des-connecteurs)
   - [Voir la liste des connecteurs](#voir-la-liste-des-connecteurs)
   - [Ajouter un connecteur](#ajouter-un-connecteur)
   - [Modifier un connecteur](#modifier-un-connecteur)
   - [Supprimer un connecteur](#supprimer-un-connecteur)
   - [Exporter les connecteurs en CSV](#exporter-les-connecteurs-en-csv)
7. [Gestion des Catalogues Modèles (Administrateurs)](#gestion-des-catalogues-modèles-administrateurs)
   - [Accéder aux catalogues modèles](#accéder-aux-catalogues-modèles)
   - [Consulter un catalogue modèle](#consulter-un-catalogue-modèle)
   - [Ajouter un modèle](#ajouter-un-modèle)
   - [Modifier un modèle](#modifier-un-modèle)
   - [Supprimer un modèle](#supprimer-un-modèle)
8. [Gestion des Utilisateurs (Administrateurs)](#gestion-des-utilisateurs-administrateurs)
   - [Consulter la liste des utilisateurs](#consulter-la-liste-des-utilisateurs)
   - [Ajouter un utilisateur](#ajouter-un-utilisateur)
   - [Modifier un utilisateur](#modifier-un-utilisateur)
   - [Supprimer un utilisateur](#supprimer-un-utilisateur)
9. [Résolution des Problèmes](#résolution-des-problèmes)
10. [Fonctionnalités à Venir](#fonctionnalités-à-venir)
11. [Conclusion](#conclusion)

---

## Introduction

Oboba est un logiciel conçu pour gérer les câblages industriels, notamment les câbles et les connecteurs, dans une application Symfony utilisable hors ligne. Ce manuel vous guide à travers les fonctionnalités actuelles pour consulter, ajouter, modifier, et exporter des données liées à vos projets.

### Rôles et niveaux d’habilitation
Votre expérience dans Oboba dépend de votre **rôle**, qui détermine ce que vous pouvez voir et faire. Les rôles sont attribués par projet (via une association dans le système) ou globalement (pour les administrateurs). Voici une explication détaillée :

- **Lecteur** :
  - **Droits** : Consultation uniquement. Vous pouvez voir les listes de projets, câbles, et connecteurs auxquels vous avez accès, ainsi que leurs détails (ex. nom, longueur, catalogue).
  - **Limites** : Vous ne pouvez ni ajouter, ni modifier, ni supprimer des données. Les boutons "Ajouter", "Modifier", et "Supprimer" ne sont pas visibles.
  - **Exemple** : Un technicien qui vérifie les câbles d’un projet sans y apporter de changements.
  - **Accès typique** : Liste des projets via "Mes Projets", listes de câbles et connecteurs avec filtres et export CSV.

- **Concepteur** :
  - **Droits** : Consultation + gestion des données dans les projets où vous êtes associé. Vous pouvez ajouter, modifier, et supprimer des câbles et connecteurs, ainsi que les exporter en CSV.
  - **Limites** : Vous ne pouvez pas gérer les utilisateurs ni les catalogues modèles globaux, et vous n’avez pas de droits sur les projets où vous n’êtes pas associé.
  - **Exemple** : Un ingénieur qui conçoit et met à jour les câblages d’un projet spécifique.
  - **Accès typique** : Tout ce que fait un lecteur, plus les actions d’édition (boutons "Ajouter", "Modifier", "Supprimer").

- **Propriétaire** :
  - **Droits** : Identiques au concepteur pour les câbles et connecteurs, avec en plus la possibilité de gérer les accès au projet (non encore implémenté dans l’interface actuelle). Vous êtes le "responsable" du projet.
  - **Limites** : Comme le concepteur, vous n’avez pas accès aux fonctionnalités globales (utilisateurs, catalogues modèles) réservées aux administrateurs.
  - **Exemple** : Un chef de projet qui supervise et ajuste un projet tout en contrôlant qui y participe.
  - **Accès typique** : Identique au concepteur pour l’instant, avec gestion des droits d’accès prévue à l’avenir.

- **Administrateur** :
  - **Droits** : Contrôle total sur le logiciel. Vous pouvez gérer les utilisateurs (ajout, modification, suppression), les catalogues modèles (câbles, connecteurs, borniers), et supprimer des projets entiers. Vous avez aussi accès à tous les projets, même sans association explicite.
  - **Limites** : Aucune dans l’état actuel, mais vos actions ont un impact global (ex. supprimer un utilisateur supprime son accès à tous les projets).
  - **Exemple** : Un gestionnaire système qui configure Oboba et maintient les données de référence.
  - **Accès typique** : Tout ce que font les autres rôles, plus "Gestion des utilisateurs" et "Catalogues modèles" dans la barre de navigation.

### Comment savoir mon rôle ?
- Après connexion, vérifiez les options dans la barre de navigation :
  - Si vous voyez "Gestion des utilisateurs" et "Catalogues modèles", vous êtes administrateur.
  - Sinon, vos droits dépendent de votre rôle par projet (vérifiable auprès d’un administrateur ou propriétaire).
- Dans les listes (câbles, connecteurs), la présence des boutons "Ajouter", "Modifier", "Supprimer" indique que vous êtes concepteur ou propriétaire pour ce projet.

Ce manuel indique clairement quand une action est réservée à un rôle spécifique (ex. "Disponible pour concepteurs et propriétaires").

---

## Connexion au Logiciel

1. Lancez le serveur local avec la commande suivante dans votre terminal :
2. Ouvrez votre navigateur et accédez à `http://localhost:8000`.
3. Sur la page de connexion :
- Entrez votre **email** (ex. `jean.dupont@example.com`).
- Entrez votre **mot de passe**.
- Cliquez sur **Connexion**.
4. Si vos identifiants sont corrects, vous serez redirigé vers la liste de vos projets.

*Note* : Si vous ne pouvez pas vous connecter, contactez un administrateur pour vérifier vos identifiants ou votre compte.

---

## Vue d’Ensemble de l’Interface

Après connexion, vous verrez :
- **Barre de navigation (en haut)** :
- Lien vers "Mes Projets" (`/projets/mes-projets`).
- Pour les administrateurs : liens supplémentaires vers "Gestion des utilisateurs" et "Catalogues modèles" (câbles, connecteurs, borniers).
- Bouton "Déconnexion".
- **Contenu principal** : Varie selon la page (liste des projets, câbles, connecteurs, etc.).
- **Messages temporaires** : Apparaissent en vert (succès) ou rouge (erreur) après une action (ex. "Câble ajouté avec succès").

---

## Gestion des Projets

### Consulter vos projets
1. Cliquez sur **Mes Projets** dans la barre de navigation ou accédez à `http://localhost:8000/projets/mes-projets`.
2. Une liste affiche les projets auxquels vous avez accès, avec :
- Le **nom** du projet (cliquable pour voir les détails).
- La **date de création** et **dernière modification**.
3. Seuls les projets où vous êtes associé (lecteur, concepteur, propriétaire) apparaissent.

### Accéder à un projet spécifique
1. Dans la liste des projets, cliquez sur le nom d’un projet (ex. "Projet 2").
2. Vous arrivez sur la page du projet (`/projets/{id}`), qui affiche :
- Le nom, les dates de création et modification.
- Des boutons pour accéder à :
  - **Liste des câbles** (`/projet/{id}/cables`).
  - **Liste des connecteurs** (`/projet/{id}/connecteurs`).
- (À venir : borniers, signaux, catalogues projet.)

*Note* : Les administrateurs peuvent aussi supprimer un projet via un bouton sur cette page (supprime tout le contenu associé).

---

## Gestion des Câbles

### Voir la liste des câbles
1. Depuis la page d’un projet, cliquez sur **Liste des câbles** ou accédez à `http://localhost:8000/projet/{projetId}/cables` (ex. `/projet/2/cables`).
2. La liste affiche :
- **Nom**, **Longueur**, **Catalogue Projet** (si défini), **Actions** (si autorisé).
- Pagination (10 câbles par page).
3. Utilisez les filtres en haut pour affiner la recherche :
- **Nom** : Tapez une partie du nom.
- **Longueur Min/Max** : Entrez des valeurs.
- **Catalogue Projet** : Sélectionnez un catalogue spécifique.
- Cliquez sur **Filtrer**.
4. Cliquez sur **Retour à <nom du projet>** pour revenir à la page du projet.

### Ajouter un câble
*Disponible pour concepteurs et propriétaires.*
1. Sur la liste des câbles, cliquez sur **Ajouter un câble**.
2. Remplissez le formulaire :
- **Nom** : Obligatoire (ex. "Câble A1").
- **Longueur** : Optionnelle (en mètres, ex. "5").
- **Catalogue Projet** : Optionnel, choisissez dans la liste déroulante.
3. Cliquez sur **Enregistrer**.
4. Un message "Câble ajouté avec succès" confirme l’action.

### Modifier un câble
*Disponible pour concepteurs et propriétaires.*
1. Dans la liste, sous "Actions", cliquez sur **Modifier** pour un câble.
2. Modifiez les champs souhaités (nom, longueur, catalogue).
3. Cliquez sur **Enregistrer**.
4. Un message "Câble modifié avec succès" apparaît.

### Supprimer un câble
*Disponible pour concepteurs et propriétaires.*
1. Dans la liste, sous "Actions", cliquez sur **Supprimer** pour un câble.
2. Confirmez la suppression dans la fenêtre qui s’ouvre.
3. Un message "Câble supprimé avec succès" confirme l’action.

### Exporter les câbles en CSV
1. Sur la liste des câbles, cliquez sur **Exporter en CSV**.
2. Un fichier `cables_{projetId}_{date}.csv` est téléchargé, contenant :
- Nom, Longueur, Catalogue Projet (nom, type, nombre de conducteurs, prix unitaire).

---

## Gestion des Connecteurs

### Voir la liste des connecteurs
1. Depuis la page d’un projet, cliquez sur **Liste des connecteurs** ou accédez à `http://localhost:8000/projet/{projetId}/connecteurs` (ex. `/projet/2/connecteurs`).
2. La liste affiche :
- **Nom**, **Catalogue Projet**, **Nombre de contacts**, **Type**, **Actions** (si autorisé).
- Pagination (10 connecteurs par page).
3. Utilisez les filtres en haut :
- **Nom** : Tapez une partie du nom.
- **Nombre de contacts** : Entrez une valeur exacte.
- **Type** : Tapez un type (ex. "USB").
- **Catalogue Projet** : Sélectionnez un catalogue.
- Cliquez sur **Filtrer**.
4. Cliquez sur **Retour à <nom du projet>** pour revenir à la page du projet.

### Ajouter un connecteur
*Disponible pour concepteurs et propriétaires.*
1. Sur la liste des connecteurs, cliquez sur **Ajouter un connecteur**.
2. Remplissez le formulaire :
- **Nom** : Obligatoire (ex. "Connecteur B1").
- **Catalogue Projet** : Optionnel, choisissez dans la liste déroulante.
3. Cliquez sur **Enregistrer**.
4. Un message "Connecteur ajouté avec succès" confirme l’action.

### Modifier un connecteur
*Disponible pour concepteurs et propriétaires.*
1. Dans la liste, sous "Actions", cliquez sur **Modifier** pour un connecteur.
2. Modifiez les champs (nom, catalogue).
3. Cliquez sur **Enregistrer**.
4. Un message "Connecteur modifié avec succès" apparaît.

### Supprimer un connecteur
*Disponible pour concepteurs et propriétaires.*
1. Dans la liste, sous "Actions", cliquez sur **Supprimer** pour un connecteur.
2. Confirmez la suppression dans la fenêtre qui s’ouvre.
3. Un message "Connecteur supprimé avec succès" confirme l’action.

### Exporter les connecteurs en CSV
1. Sur la liste des connecteurs, cliquez sur **Exporter en CSV**.
2. Un fichier `connecteurs_{projetId}_{date}.csv` est téléchargé, contenant :
- Nom, Catalogue Projet (nom, nombre de contacts, type, prix unitaire).

---

## Gestion des Catalogues Modèles (Administrateurs)

### Accéder aux catalogues modèles
1. Dans la barre de navigation, sous "Catalogues modèles", choisissez :
- **Câbles** (`/admin/catalogue/modele/cables`).
- **Connecteurs** (`/admin/catalogue/modele/connecteurs`).
- **Borniers** (`/admin/catalogue/modele/borniers`).
*Note* : Visible uniquement pour les administrateurs.

### Consulter un catalogue modèle
1. Cliquez sur un catalogue (ex. "Câbles").
2. La liste affiche les modèles avec leurs détails (nom, type, nombre de conducteurs/contacts/bornes, prix unitaire).
3. Utilisez les filtres pour affiner la recherche (ex. nom, prix minimum/maximum).

### Ajouter un modèle
1. Sur la liste d’un catalogue, cliquez sur **Ajouter**.
2. Remplissez le formulaire (tous les champs sont obligatoires sauf indication) :
- **Câbles** : Nom, Type, Nombre de conducteurs max, Prix unitaire.
- **Connecteurs** : Nom, Type, Nombre de contacts, Prix unitaire.
- **Borniers** : Nom, Nombre de bornes, Caractéristiques (optionnel), Prix unitaire.
3. Cliquez sur **Enregistrer**.
4. Un message confirme l’ajout.

### Modifier un modèle
1. Dans la liste, cliquez sur **Modifier** pour un modèle.
2. Ajustez les champs nécessaires.
3. Cliquez sur **Enregistrer**.
4. Un message confirme la modification.

### Supprimer un modèle
1. Dans la liste, cliquez sur **Supprimer** pour un modèle.
2. Confirmez la suppression.
3. Un message confirme l’action.

---

## Gestion des Utilisateurs (Administrateurs)

### Consulter la liste des utilisateurs
1. Dans la barre de navigation, cliquez sur **Gestion des utilisateurs** (`/utilisateurs/gestion`).
2. La liste affiche chaque utilisateur avec nom, email, et rôles.

### Ajouter un utilisateur
1. Cliquez sur **Ajouter un utilisateur**.
2. Remplissez :
- **Nom** (ex. "Jean Dupont").
- **Email** (ex. "jean.dupont@example.com").
- **Mot de passe** (requis pour la création).
- **Rôles** : Cochez "Administrateur" si nécessaire.
3. Cliquez sur **Enregistrer**.
4. L’utilisateur est ajouté.

### Modifier un utilisateur
1. Dans la liste, cliquez sur **Modifier** pour un utilisateur.
2. Changez le nom, email, ou rôles (le mot de passe est optionnel).
3. Cliquez sur **Enregistrer**.

### Supprimer un utilisateur
1. Dans la liste, cliquez sur **Supprimer** pour un utilisateur.
2. Confirmez la suppression.
3. L’utilisateur est retiré.

---

## Résolution des Problèmes

- **"Accès refusé"** : Vérifiez votre rôle pour le projet (lecteur n’a pas les droits d’édition). Contactez un administrateur ou propriétaire.
- **Liste vide** : Assurez-vous que des données existent ou que les filtres ne sont pas trop restrictifs.
- **Erreur lors de l’ajout/modification** : Vérifiez que tous les champs obligatoires (ex. nom) sont remplis.
- **Problème de connexion** : Confirmez vos identifiants avec un administrateur.

---

## Fonctionnalités à Venir

Ces fonctionnalités sont prévues mais pas encore disponibles :
- **Gestion des borniers** : Ajout, modification, suppression.
- **Signaux (WireSignal)** : Consultation et gestion des signaux transportés par les câbles.
- **Propagation des signaux** : Visualisation des connexions entre conducteurs, bornes, et contacts.
- **Import/Export complet d’un projet** : Sauvegarde et restauration des données.
- **Rapports** : Génération de documents détaillés.

---

## Conclusion

Oboba vous permet actuellement de gérer vos projets, câbles, connecteurs, et catalogues modèles de manière efficace. Utilisez les listes pour consulter vos données, les boutons pour naviguer facilement (ex. "Retour à <nom du projet>"), et les outils d’exportation pour sauvegarder vos informations. Les administrateurs ont un contrôle total sur les utilisateurs et les catalogues modèles. Pour toute question ou suggestion, contactez l’équipe de développement.

Bonne utilisation d’Oboba !
