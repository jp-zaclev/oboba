-- Utilisation de la base de données
USE oboba;

-- Désactiver temporairement les contraintes de clés étrangères pour faciliter l'insertion
SET FOREIGN_KEY_CHECKS = 0;

-- Table: utilisateur
INSERT INTO utilisateur (nom, email, password, roles) VALUES
('Jean Dupont', 'jean.dupont@example.com', '$2y$13$dyPC4M0YfS3TjVdtgeJtXu5fTEDreYROYFFU20xwYXf0Wecuebmsy', '["ROLE_ADMIN"]'),
('Marie Curie', 'marie.curie@example.com', '$2y$13$dyPC4M0YfS3TjVdtgeJtXu5fTEDreYROYFFU20xwYXf0Wecuebmsy', '["ROLE_USER"]'),
('Paul Martin', 'paul.martin@example.com', '$2y$13$dyPC4M0YfS3TjVdtgeJtXu5fTEDreYROYFFU20xwYXf0Wecuebmsy', '["ROLE_USER"]');

-- Table: projet
INSERT INTO projet (nom, date_heure_creation, date_heure_derniere_modification) VALUES
('Projet Alpha', '2025-03-17 08:00:00', '2025-03-17 08:00:00'),
('Projet Beta', '2025-03-17 09:00:00', '2025-03-17 09:00:00');

-- Table: projet_utilisateur
INSERT INTO projet_utilisateur (projet_id, utilisateur_id, role) VALUES
(1, 1, 'proprietaire'),
(1, 2, 'concepteur'),
(2, 2, 'proprietaire'),
(2, 3, 'lecteur');

-- Table: signal
INSERT INTO `signal` (id_projet, nom, type) VALUES
(1, 'Signal Température', 'analogique'),
(1, 'Signal Alarme', 'digital'),
(2, 'Signal Pression', 'analogique');

-- Table: catalogue_modele_cables
INSERT INTO catalogue_modele_cables (nom, nombre_conducteurs, prix_unitaire) VALUES
('Câble 2 Conducteurs', 2, 5.50),
('Câble 4 Conducteurs', 4, 8.75);

-- Table: catalogue_projet_cables
INSERT INTO catalogue_projet_cables (id_projet, nom, nombre_conducteurs, prix_unitaire) VALUES
(1, 'Câble Projet Alpha 2C', 2, 6.00),
(2, 'Câble Projet Beta 4C', 4, 9.00);

-- Table: cable
INSERT INTO cable (id_catalogue_projet_cable, id_projet, nom) VALUES
(1, 1, 'Câble Alpha 1'),
(2, 2, 'Câble Beta 1');

-- Table: catalogue_modele_borniers
INSERT INTO catalogue_modele_borniers (nom, nombre_bornes, prix_unitaire) VALUES
('Bornier 8 Bornes', 8, 12.00),
('Bornier 16 Bornes', 16, 20.00);

-- Table: catalogue_projet_borniers
INSERT INTO catalogue_projet_borniers (id_projet, nom, nombre_bornes, prix_unitaire) VALUES
(1, 'Bornier Alpha 8', 8, 13.00),
(2, 'Bornier Beta 16', 16, 22.00);

-- Table: bornier
INSERT INTO bornier (id_catalogue_projet_bornier, id_projet, nom, localisation) VALUES
(1, 1, 'Bornier Alpha 1', 'Armoire 1'),
(2, 2, 'Bornier Beta 1', 'Armoire 2');

-- Table: borne
INSERT INTO borne (id_bornier, identifiant) VALUES
(1, 'B1'),
(1, 'B2'),
(2, 'B1');

-- Table: catalogue_modele_connecteurs
INSERT INTO catalogue_modele_connecteurs (nom, nombre_contacts, type, prix_unitaire) VALUES
('Connecteur DB9', 9, 'Série', 3.50),
('Connecteur RJ45', 8, 'Réseau', 2.00);

-- Table: catalogue_projet_connecteurs
INSERT INTO catalogue_projet_connecteurs (id_projet, nom, nombre_contacts, type, prix_unitaire) VALUES
(1, 'Connecteur Alpha DB9', 9, 'Série', 4.00),
(2, 'Connecteur Beta RJ45', 8, 'Réseau', 2.50);

-- Table: equipement
INSERT INTO equipement (id_projet, nom, reference) VALUES
(1, 'Capteur Température', 'TEMP-001'),
(2, 'Relais', 'REL-001');

-- Table: connecteur
INSERT INTO connecteur (id_catalogue_projet_connecteur, id_projet, id_equipement, nom, localisation) VALUES
(1, 1, 1, 'Connecteur Alpha 1', 'Panneau A'),
(2, 2, 2, 'Connecteur Beta 1', 'Panneau B');

-- Table: contact
INSERT INTO contact (id_connecteur, identifiant, type) VALUES
(1, 'Pin 1', 'emission'),
(1, 'Pin 2', 'reception'),
(2, 'Pin 1', 'emission_reception');

-- Table: conducteur
INSERT INTO conducteur (id_cable, id_signal, id_borne_source, id_borne_destination, id_contact_source, id_contact_destination, attribut) VALUES
(1, 1, 1, 2, NULL, NULL, 'Conducteur 1'),
(2, 2, 3, NULL, 1, 2, 'Conducteur 2');

-- Réactiver les contraintes de clés étrangères
SET FOREIGN_KEY_CHECKS = 1;
