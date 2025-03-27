-- Désactiver temporairement les contraintes pour insérer les données
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Projets
INSERT INTO projet (id, nom, date_heure_creation, date_heure_derniere_modification) VALUES
(1, 'Projet Alpha', '2025-03-27 10:00:00', '2025-03-27 10:00:00'),
(2, 'Projet Beta', '2025-03-27 11:00:00', '2025-03-27 11:00:00');

-- 2. Associer l’admin aux projets
INSERT INTO projet_utilisateur (id, projet_id, utilisateur_id, role) VALUES
(1, 1, 1, 'Administrateur'),
(2, 2, 1, 'Administrateur');

-- 3. Localisations
INSERT INTO localisation (id, id_projet, nom, x, y, z) VALUES
(1, 1, 'Armoire 1 - Rangée 1', 10.5, 20.0, NULL),
(2, 1, 'Salle serveur', NULL, NULL, NULL),
(3, 2, 'Zone A - Étage 1', 15.0, 25.0, 0.0),
(4, 2, 'Zone B - Sous-sol', 15.0, 30.0, -2.5);

-- 4. Catalogue des modèles de borniers (global)
INSERT INTO catalogue_modele_borniers (id, nom, nombre_bornes, caracteristiques, prix_unitaire) VALUES
(1, 'Bornier à vis 2P', 2, '2.5mm² max', 0.80),
(2, 'Bornier à vis 4P', 4, '4mm² max', 1.20),
(3, 'Bornier à ressort 2P', 2, '1.5mm² max', 0.90),
(4, 'Bornier DIN 2P', 2, '10mm² max', 1.00),
(5, 'Bornier PCB 3P', 3, '1.5mm² max', 0.80);

-- 5. Catalogue des borniers par projet
INSERT INTO catalogue_projet_borniers (id, id_projet, nom, nombre_bornes, caracteristiques, prix_unitaire) VALUES
(1, 1, 'Bornier à vis 2P', 2, '2.5mm² max', 0.80),
(2, 1, 'Bornier à ressort 4P', 4, '2.5mm² max', 1.40),
(3, 2, 'Bornier DIN 2P', 2, '10mm² max', 1.00),
(4, 2, 'Bornier PCB 3P', 3, '1.5mm² max', 0.80);

-- 6. Borniers
INSERT INTO bornier (id, id_catalogue_projet_bornier, id_projet, id_localisation, nom) VALUES
(1, 1, 1, 1, 'Bornier A1'),
(2, 2, 1, 2, 'Bornier B1'),
(3, 3, 2, 3, 'Bornier C2'),
(4, 4, 2, 4, 'Bornier D2');

-- 7. Bornes
INSERT INTO borne (id, id_bornier, identification) VALUES
(1, 1, 'Borne 1'),
(2, 1, 'Borne 2'),
(3, 2, 'Borne 1'),
(4, 2, 'Borne 2'),
(5, 3, 'Borne 1'),
(6, 4, 'Borne 1');

-- 8. Catalogue des modèles de câbles (global)
INSERT INTO catalogue_modele_cables (id, nom, nombre_conducteurs_max, prix_unitaire, type) VALUES
(1, 'Câble unipolaire H07V-U 1.5mm²', 1, 0.50, 'Unipolaire'),
(2, 'Câble multipaire 2x1.5mm²', 2, 1.00, 'Multipaire'),
(3, 'Câble blindé CY 2x0.75mm²', 2, 1.50, 'Blindé'),
(4, 'Câble réseau UTP Cat5e', 8, 0.90, 'Réseau'),
(5, 'Câble coaxial RG58', 1, 1.10, 'Coaxial');

-- 9. Catalogue des câbles par projet
INSERT INTO catalogue_projet_cables (id, id_projet, nom, nombre_conducteurs_max, prix_unitaire, type) VALUES
(1, 1, 'Câble unipolaire H07V-U 1.5mm²', 1, 0.50, 'Unipolaire'),
(2, 1, 'Câble multipaire 2x1.5mm²', 2, 1.00, 'Multipaire'),
(3, 2, 'Câble blindé CY 2x0.75mm²', 2, 1.50, 'Blindé'),
(4, 2, 'Câble réseau UTP Cat5e', 8, 0.90, 'Réseau');

-- 10. Câbles
INSERT INTO cable (id, id_projet, id_catalogue_projet_cable, nom, longueur) VALUES
(1, 1, 1, 'Câble A1', 5),
(2, 1, 2, 'Câble B1', 10),
(3, 2, 3, 'Câble C2', 15),
(4, 2, 4, 'Câble D2', 20);

-- 11. Catalogue des modèles de connecteurs (global)
INSERT INTO catalogue_modele_connecteurs (id, nom, nombre_contacts, type, prix_unitaire) VALUES
(1, 'Connecteur DB9 Mâle', 9, 'Série', 1.50),
(2, 'Connecteur RJ45 Mâle', 8, 'Réseau', 0.80),
(3, 'Connecteur USB-A Mâle', 4, 'USB', 0.70),
(4, 'Connecteur XLR 3P Mâle', 3, 'Audio', 2.00),
(5, 'Connecteur Molex Mini-Fit 4P', 4, 'Molex', 1.20);

-- 12. Catalogue des connecteurs par projet
INSERT INTO catalogue_projet_connecteurs (id, id_projet, nom, nombre_contacts, type, prix_unitaire) VALUES
(1, 1, 'Connecteur DB9 Mâle', 9, 'Série', 1.50),
(2, 1, 'Connecteur RJ45 Mâle', 8, 'Réseau', 0.80),
(3, 2, 'Connecteur USB-A Mâle', 4, 'USB', 0.70),
(4, 2, 'Connecteur XLR 3P Mâle', 3, 'Audio', 2.00);

-- 13. Équipements
INSERT INTO equipement (id, id_projet, nom, reference) VALUES
(1, 1, 'Serveur principal', 'SRV-001'),
(2, 2, 'Switch réseau', 'SWT-002');

-- 14. Connecteurs
INSERT INTO connecteur (id, id_projet, id_catalogue_projet_connecteur, id_equipement, nom) VALUES
(1, 1, 1, 1, 'Connecteur DB9 S1'),
(2, 1, 2, NULL, 'Connecteur RJ45 C1'),
(3, 2, 3, 2, 'Connecteur USB SW1'),
(4, 2, 4, NULL, 'Connecteur XLR A1');

-- 15. Contacts
INSERT INTO contact (id, id_connecteur, identifiant, type) VALUES
(1, 1, 'Pin 1', 'Mâle'),
(2, 1, 'Pin 2', 'Mâle'),
(3, 2, 'Pin 1', 'Mâle'),
(4, 3, 'Pin 1', 'Mâle'),
(5, 4, 'Pin 1', 'Mâle');

-- 16. Signaux
INSERT INTO wire_signal (id, id_projet, nom, type, details) VALUES
(1, 1, 'Signal Alim', 'Power', '24V DC'),
(2, 1, 'Signal Data', 'Data', 'Ethernet'),
(3, 2, 'Signal Audio', 'Audio', 'Mono');

-- 17. Conducteurs
INSERT INTO conducteur (id, id_cable, id_borne_source, id_borne_destination, id_contact_source, id_contact_destination, id_wire_signal, attribut) VALUES
(1, 1, 1, NULL, NULL, 1, 1, 'Conducteur 1'),
(2, 2, 3, 4, NULL, NULL, 2, 'Conducteur 1'),
(3, 3, 5, NULL, 4, NULL, 3, 'Conducteur 1');

-- Réactiver les contraintes
SET FOREIGN_KEY_CHECKS = 1;
