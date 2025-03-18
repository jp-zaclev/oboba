-- Création de la base de données
DROP DATABASE IF EXISTS oboba;
CREATE DATABASE oboba CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE oboba;

-- Table: utilisateur
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(180) NOT NULL,
    password VARCHAR(255) NOT NULL,
    roles LONGTEXT NOT NULL COMMENT '(DC2Type:json)',
    UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: projet
CREATE TABLE projet (
    id INT AUTO_INCREMENT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    date_heure_creation DATETIME NOT NULL,
    date_heure_derniere_modification DATETIME NOT NULL,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: projet_utilisateur
CREATE TABLE projet_utilisateur (
    id INT AUTO_INCREMENT NOT NULL,
    projet_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    role ENUM('proprietaire', 'concepteur', 'lecteur') NOT NULL,
    INDEX IDX_C626378DC18272 (projet_id),
    INDEX IDX_C626378DFB88E14F (utilisateur_id),
    PRIMARY KEY (id),
    CONSTRAINT FK_C626378DC18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE,
    CONSTRAINT FK_C626378DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: signal
CREATE TABLE `signal` (
    id INT AUTO_INCREMENT NOT NULL,
    id_projet INT NOT NULL,
    nom VARCHAR(50) NOT NULL,
    type ENUM('analogique', 'digital') NOT NULL,
    UNIQUE INDEX UNIQ_740C95F56C6E55B5 (nom),
    INDEX IDX_740C95F576222944 (id_projet),
    PRIMARY KEY (id),
    CONSTRAINT FK_740C95F576222944 FOREIGN KEY (id_projet) REFERENCES projet (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: catalogue_modele_cables
CREATE TABLE catalogue_modele_cables (
    id INT AUTO_INCREMENT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    nombre_conducteurs INT NOT NULL,
    prix_unitaire NUMERIC(10, 2) NOT NULL,
    UNIQUE INDEX UNIQ_5216F85D6C6E55B5 (nom),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: catalogue_projet_cables
CREATE TABLE catalogue_projet_cables (
    id INT AUTO_INCREMENT NOT NULL,
    id_projet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    nombre_conducteurs INT NOT NULL,
    prix_unitaire NUMERIC(10, 2) NOT NULL,
    INDEX IDX_FD1289CC76222944 (id_projet),
    PRIMARY KEY (id),
    CONSTRAINT FK_FD1289CC76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: cable
CREATE TABLE cable (
    id INT AUTO_INCREMENT NOT NULL,
    id_catalogue_projet_cable INT NOT NULL,
    id_projet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    INDEX IDX_24E9C4D47FCB71D8 (id_catalogue_projet_cable),
    INDEX IDX_24E9C4D476222944 (id_projet),
    UNIQUE INDEX nom_projet_unique (nom, id_projet),
    PRIMARY KEY (id),
    CONSTRAINT FK_24E9C4D47FCB71D8 FOREIGN KEY (id_catalogue_projet_cable) REFERENCES catalogue_projet_cables (id),
    CONSTRAINT FK_24E9C4D476222944 FOREIGN KEY (id_projet) REFERENCES projet (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: catalogue_modele_borniers
CREATE TABLE catalogue_modele_borniers (
    id INT AUTO_INCREMENT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    nombre_bornes INT NOT NULL,
    prix_unitaire NUMERIC(10, 2) NOT NULL,
    UNIQUE INDEX UNIQ_9E51686E6C6E55B5 (nom),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: catalogue_projet_borniers
CREATE TABLE catalogue_projet_borniers (
    id INT AUTO_INCREMENT NOT NULL,
    id_projet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    nombre_bornes INT NOT NULL,
    prix_unitaire NUMERIC(10, 2) NOT NULL,
    INDEX IDX_D1093D9B76222944 (id_projet),
    PRIMARY KEY (id),
    CONSTRAINT FK_D1093D9B76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: bornier
CREATE TABLE bornier (
    id INT AUTO_INCREMENT NOT NULL,
    id_catalogue_projet_bornier INT NOT NULL,
    id_projet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    localisation VARCHAR(255) NOT NULL,
    INDEX IDX_C18B73FFD63520D2 (id_catalogue_projet_bornier),
    INDEX IDX_C18B73FF76222944 (id_projet),
    UNIQUE INDEX nom_projet_unique (nom, id_projet),
    PRIMARY KEY (id),
    CONSTRAINT FK_C18B73FFD63520D2 FOREIGN KEY (id_catalogue_projet_bornier) REFERENCES catalogue_projet_borniers (id),
    CONSTRAINT FK_C18B73FF76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: borne
CREATE TABLE borne (
    id INT AUTO_INCREMENT NOT NULL,
    id_bornier INT NOT NULL,
    identifiant VARCHAR(50) NOT NULL,
    INDEX IDX_D7465BA51F16DA8F (id_bornier),
    PRIMARY KEY (id),
    CONSTRAINT FK_D7465BA51F16DA8F FOREIGN KEY (id_bornier) REFERENCES bornier (id) ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: catalogue_modele_connecteurs
CREATE TABLE catalogue_modele_connecteurs (
    id INT AUTO_INCREMENT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    nombre_contacts INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    prix_unitaire NUMERIC(10, 2) NOT NULL,
    UNIQUE INDEX UNIQ_EACE340D6C6E55B5 (nom),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: catalogue_projet_connecteurs
CREATE TABLE catalogue_projet_connecteurs (
    id INT AUTO_INCREMENT NOT NULL,
    id_projet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    nombre_contacts INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    prix_unitaire NUMERIC(10, 2) NOT NULL,
    INDEX IDX_FBBDF5AE76222944 (id_projet),
    PRIMARY KEY (id),
    CONSTRAINT FK_FBBDF5AE76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: equipement
CREATE TABLE equipement (
    id INT AUTO_INCREMENT NOT NULL,
    id_projet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    reference VARCHAR(50) NOT NULL,
    INDEX IDX_B8B4C6F376222944 (id_projet),
    PRIMARY KEY (id),
    CONSTRAINT FK_B8B4C6F376222944 FOREIGN KEY (id_projet) REFERENCES projet (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;



-- Table: connecteur
CREATE TABLE connecteur (
    id INT AUTO_INCREMENT NOT NULL,
    id_catalogue_projet_connecteur INT NOT NULL,
    id_projet INT NOT NULL,
    id_equipement INT DEFAULT NULL,
    nom VARCHAR(255) NOT NULL,
    localisation VARCHAR(255) DEFAULT NULL,
    INDEX IDX_84C12C9691B692E (id_catalogue_projet_connecteur),
    INDEX IDX_84C12C9676222944 (id_projet),
    INDEX IDX_84C12C961D3E4624 (id_equipement),
    PRIMARY KEY (id),
    CONSTRAINT FK_84C12C9691B692E FOREIGN KEY (id_catalogue_projet_connecteur) REFERENCES catalogue_projet_connecteurs (id),
    CONSTRAINT FK_84C12C9676222944 FOREIGN KEY (id_projet) REFERENCES projet (id),
    CONSTRAINT FK_84C12C961D3E4624 FOREIGN KEY (id_equipement) REFERENCES equipement (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: contact
CREATE TABLE contact (
    id INT AUTO_INCREMENT NOT NULL,
    id_connecteur INT NOT NULL,
    identifiant VARCHAR(50) NOT NULL,
    type ENUM('emission', 'reception', 'emission_reception') NOT NULL,
    INDEX IDX_4C62E638214BAC41 (id_connecteur),
    PRIMARY KEY (id),
    CONSTRAINT FK_4C62E638214BAC41 FOREIGN KEY (id_connecteur) REFERENCES connecteur (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Table: conducteur
CREATE TABLE conducteur (
    id INT AUTO_INCREMENT NOT NULL,
    id_cable INT NOT NULL,
    id_signal INT NOT NULL,
    id_borne_source INT DEFAULT NULL,
    id_borne_destination INT DEFAULT NULL,
    id_contact_source INT DEFAULT NULL,
    id_contact_destination INT DEFAULT NULL,
    attribut VARCHAR(50) NOT NULL,
    INDEX IDX_23677143CA6C85E4 (id_cable),
    INDEX IDX_23677143523B2018 (id_signal),
    INDEX IDX_236771439A92C5B6 (id_borne_source),
    INDEX IDX_23677143C944263C (id_borne_destination),
    INDEX IDX_23677143F27EF5F4 (id_contact_source),
    INDEX IDX_2367714316801DDF (id_contact_destination),
    PRIMARY KEY (id),
    CONSTRAINT FK_23677143CA6C85E4 FOREIGN KEY (id_cable) REFERENCES cable (id) ON DELETE CASCADE,
    CONSTRAINT FK_23677143523B2018 FOREIGN KEY (id_signal) REFERENCES `signal` (id),
    CONSTRAINT FK_236771439A92C5B6 FOREIGN KEY (id_borne_source) REFERENCES borne (id),
    CONSTRAINT FK_23677143C944263C FOREIGN KEY (id_borne_destination) REFERENCES borne (id),
    CONSTRAINT FK_23677143F27EF5F4 FOREIGN KEY (id_contact_source) REFERENCES contact (id),
    CONSTRAINT FK_2367714316801DDF FOREIGN KEY (id_contact_destination) REFERENCES contact (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
