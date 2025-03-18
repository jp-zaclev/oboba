-- Création de la base de données
CREATE DATABASE IF NOT EXISTS oboba;
USE oboba;

-- Table : utilisateur
CREATE TABLE utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL -- Haché avec bcrypt
);

-- Table : projet
CREATE TABLE projet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    id_utilisateur_proprietaire INT NOT NULL,
    date_heure_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_heure_derniere_modification DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur_proprietaire) REFERENCES utilisateur(id)
);

-- Table de liaison : projet_utilisateur (pour rôles lecteur/concepteur)
CREATE TABLE projet_utilisateur (
    id_projet INT NOT NULL,
    id_utilisateur INT NOT NULL,
    role ENUM('lecteur', 'concepteur') NOT NULL,
    PRIMARY KEY (id_projet, id_utilisateur),
    FOREIGN KEY (id_projet) REFERENCES projet(id) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE
);

-- Table : signal
CREATE TABLE `signal` (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL UNIQUE,
    type ENUM('analogique', 'digital') NOT NULL,
    details VARCHAR(255),
    id_projet INT NOT NULL,
    FOREIGN KEY (id_projet) REFERENCES projet(id)
);

-- Table : catalogue_modele_cables
CREATE TABLE catalogue_modele_cables (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    nombre_conducteurs_max INT NOT NULL,
    prix_metre DECIMAL(10,2) NOT NULL
);

-- Table : catalogue_projet_cables
CREATE TABLE catalogue_projet_cables (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_projet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    nombre_conducteurs_max INT NOT NULL,
    prix_metre DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_projet) REFERENCES projet(id)
);

-- Table : cable
CREATE TABLE cable (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    id_catalogue_projet_cable INT NOT NULL,
    id_projet INT NOT NULL,
    longueur DECIMAL(10,2) NOT NULL DEFAULT 0 CHECK (longueur >= 0 AND longueur <= 1000),
    UNIQUE KEY uniq_nom_projet (nom, id_projet), -- Unicité du nom dans un projet
    FOREIGN KEY (id_catalogue_projet_cable) REFERENCES catalogue_projet_cables(id),
    FOREIGN KEY (id_projet) REFERENCES projet(id)
);

-- Table : conducteur
CREATE TABLE conducteur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_cable INT NOT NULL,
    attribut VARCHAR(255) NOT NULL,
    id_extremite_source INT NULL,
    id_extremite_destination INT NULL,
    id_signal INT NULL,
    CHECK (id_extremite_source != id_extremite_destination), -- Source et destination distinctes
    FOREIGN KEY (id_cable) REFERENCES cable(id) ON DELETE CASCADE,
    FOREIGN KEY (id_signal) REFERENCES `signal`(id)
);

-- Table : catalogue_modele_borniers
CREATE TABLE catalogue_modele_borniers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    nombre_bornes INT NOT NULL,
    caracteristiques VARCHAR(255),
    prix_unitaire DECIMAL(10,2) NOT NULL
);

-- Table : catalogue_projet_borniers
CREATE TABLE catalogue_projet_borniers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_projet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    nombre_bornes INT NOT NULL,
    caracteristiques VARCHAR(255),
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_projet) REFERENCES projet(id)
);

-- Table : bornier
CREATE TABLE bornier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    id_catalogue_projet_bornier INT NOT NULL,
    id_projet INT NOT NULL,
    localisation VARCHAR(255),
    UNIQUE KEY uniq_nom_projet (nom, id_projet), -- Unicité du nom dans un projet
    FOREIGN KEY (id_catalogue_projet_bornier) REFERENCES catalogue_projet_borniers(id),
    FOREIGN KEY (id_projet) REFERENCES projet(id)
);

-- Table : borne
CREATE TABLE borne (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_bornier INT NOT NULL,
    identification VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_bornier) REFERENCES bornier(id) ON DELETE CASCADE
);

-- Table : catalogue_modele_connecteurs
CREATE TABLE catalogue_modele_connecteurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    nombre_contacts INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    prix_unitaire DECIMAL
);
