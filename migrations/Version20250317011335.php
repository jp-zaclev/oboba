<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250317011335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE borne (id INT AUTO_INCREMENT NOT NULL, id_bornier INT NOT NULL, identification VARCHAR(50) NOT NULL, INDEX IDX_D7465BA51F16DA8F (id_bornier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bornier (id INT AUTO_INCREMENT NOT NULL, id_catalogue_projet_bornier INT NOT NULL, id_projet INT NOT NULL, nom VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, INDEX IDX_C18B73FFD63520D2 (id_catalogue_projet_bornier), INDEX IDX_C18B73FF76222944 (id_projet), UNIQUE INDEX nom_projet_unique (nom, id_projet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cable (id INT AUTO_INCREMENT NOT NULL, id_catalogue_projet_cable INT NOT NULL, id_projet INT NOT NULL, nom VARCHAR(50) NOT NULL, longueur NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL, INDEX IDX_24E9C4D47FCB71D8 (id_catalogue_projet_cable), INDEX IDX_24E9C4D476222944 (id_projet), UNIQUE INDEX nom_projet_unique (nom, id_projet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue_modele_borniers (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, nombre_bornes INT NOT NULL, caracteristiques VARCHAR(255) DEFAULT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_9E51686E6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue_modele_cables (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, nombre_conducteurs_max INT NOT NULL, prix_metre NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_5216F85D6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue_modele_connecteurs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, nombre_contacts INT NOT NULL, type VARCHAR(50) NOT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_EACE340D6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue_projet_borniers (id INT AUTO_INCREMENT NOT NULL, id_projet INT NOT NULL, nom VARCHAR(255) NOT NULL, nombre_bornes INT NOT NULL, caracteristiques VARCHAR(255) DEFAULT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, INDEX IDX_D1093D9B76222944 (id_projet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue_projet_cables (id INT AUTO_INCREMENT NOT NULL, id_projet INT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, nombre_conducteurs_max INT NOT NULL, prix_metre NUMERIC(10, 2) NOT NULL, INDEX IDX_FD1289CC76222944 (id_projet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue_projet_connecteurs (id INT AUTO_INCREMENT NOT NULL, id_projet INT NOT NULL, nom VARCHAR(255) NOT NULL, nombre_contacts INT NOT NULL, type VARCHAR(50) NOT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, INDEX IDX_FBBDF5AE76222944 (id_projet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conducteur (id INT AUTO_INCREMENT NOT NULL, id_cable INT NOT NULL, id_borne_source INT DEFAULT NULL, id_borne_destination INT DEFAULT NULL, id_contact_source INT DEFAULT NULL, id_contact_destination INT DEFAULT NULL, id_signal INT DEFAULT NULL, attribut VARCHAR(255) NOT NULL, INDEX IDX_23677143CA6C85E4 (id_cable), INDEX IDX_236771439A92C5B6 (id_borne_source), INDEX IDX_23677143C944263C (id_borne_destination), INDEX IDX_23677143F27EF5F4 (id_contact_source), INDEX IDX_2367714316801DDF (id_contact_destination), INDEX IDX_23677143523B2018 (id_signal), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE connecteur (id INT AUTO_INCREMENT NOT NULL, id_catalogue_projet_connecteur INT NOT NULL, id_projet INT NOT NULL, id_equipement INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, localisation VARCHAR(255) DEFAULT NULL, INDEX IDX_84C12C9691B692E (id_catalogue_projet_connecteur), INDEX IDX_84C12C9676222944 (id_projet), INDEX IDX_84C12C961D3E4624 (id_equipement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, id_connecteur INT NOT NULL, identifiant VARCHAR(50) NOT NULL, type ENUM(\'emission\', \'reception\', \'emission_reception\'), INDEX IDX_4C62E638214BAC41 (id_connecteur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, id_projet INT NOT NULL, nom VARCHAR(255) NOT NULL, reference VARCHAR(50) NOT NULL, INDEX IDX_B8B4C6F376222944 (id_projet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date_heure_creation DATETIME NOT NULL, date_heure_derniere_modification DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet_utilisateur (id INT AUTO_INCREMENT NOT NULL, projet_id INT NOT NULL, utilisateur_id INT NOT NULL, role ENUM(\'proprietaire\', \'concepteur\', \'lecteur\'), INDEX IDX_C626378DC18272 (projet_id), INDEX IDX_C626378DFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `signal` (id INT AUTO_INCREMENT NOT NULL, id_projet INT NOT NULL, nom VARCHAR(50) NOT NULL, type ENUM(\'analogique\', \'digital\'), details VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_740C95F56C6E55B5 (nom), INDEX IDX_740C95F576222944 (id_projet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE borne ADD CONSTRAINT FK_D7465BA51F16DA8F FOREIGN KEY (id_bornier) REFERENCES bornier (id)');
        $this->addSql('ALTER TABLE bornier ADD CONSTRAINT FK_C18B73FFD63520D2 FOREIGN KEY (id_catalogue_projet_bornier) REFERENCES catalogue_projet_borniers (id)');
        $this->addSql('ALTER TABLE bornier ADD CONSTRAINT FK_C18B73FF76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE cable ADD CONSTRAINT FK_24E9C4D47FCB71D8 FOREIGN KEY (id_catalogue_projet_cable) REFERENCES catalogue_projet_cables (id)');
        $this->addSql('ALTER TABLE cable ADD CONSTRAINT FK_24E9C4D476222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE catalogue_projet_borniers ADD CONSTRAINT FK_D1093D9B76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE catalogue_projet_cables ADD CONSTRAINT FK_FD1289CC76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE catalogue_projet_connecteurs ADD CONSTRAINT FK_FBBDF5AE76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_23677143CA6C85E4 FOREIGN KEY (id_cable) REFERENCES cable (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_236771439A92C5B6 FOREIGN KEY (id_borne_source) REFERENCES borne (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_23677143C944263C FOREIGN KEY (id_borne_destination) REFERENCES borne (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_23677143F27EF5F4 FOREIGN KEY (id_contact_source) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_2367714316801DDF FOREIGN KEY (id_contact_destination) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_23677143523B2018 FOREIGN KEY (id_signal) REFERENCES `signal` (id)');
        $this->addSql('ALTER TABLE connecteur ADD CONSTRAINT FK_84C12C9691B692E FOREIGN KEY (id_catalogue_projet_connecteur) REFERENCES catalogue_projet_connecteurs (id)');
        $this->addSql('ALTER TABLE connecteur ADD CONSTRAINT FK_84C12C9676222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE connecteur ADD CONSTRAINT FK_84C12C961D3E4624 FOREIGN KEY (id_equipement) REFERENCES equipement (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638214BAC41 FOREIGN KEY (id_connecteur) REFERENCES connecteur (id)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F376222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE projet_utilisateur ADD CONSTRAINT FK_C626378DC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE projet_utilisateur ADD CONSTRAINT FK_C626378DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE `signal` ADD CONSTRAINT FK_740C95F576222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_236771439A92C5B6');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_23677143C944263C');
        $this->addSql('ALTER TABLE borne DROP FOREIGN KEY FK_D7465BA51F16DA8F');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_23677143CA6C85E4');
        $this->addSql('ALTER TABLE bornier DROP FOREIGN KEY FK_C18B73FFD63520D2');
        $this->addSql('ALTER TABLE cable DROP FOREIGN KEY FK_24E9C4D47FCB71D8');
        $this->addSql('ALTER TABLE connecteur DROP FOREIGN KEY FK_84C12C9691B692E');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638214BAC41');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_23677143F27EF5F4');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_2367714316801DDF');
        $this->addSql('ALTER TABLE connecteur DROP FOREIGN KEY FK_84C12C961D3E4624');
        $this->addSql('ALTER TABLE bornier DROP FOREIGN KEY FK_C18B73FF76222944');
        $this->addSql('ALTER TABLE cable DROP FOREIGN KEY FK_24E9C4D476222944');
        $this->addSql('ALTER TABLE catalogue_projet_borniers DROP FOREIGN KEY FK_D1093D9B76222944');
        $this->addSql('ALTER TABLE catalogue_projet_cables DROP FOREIGN KEY FK_FD1289CC76222944');
        $this->addSql('ALTER TABLE catalogue_projet_connecteurs DROP FOREIGN KEY FK_FBBDF5AE76222944');
        $this->addSql('ALTER TABLE connecteur DROP FOREIGN KEY FK_84C12C9676222944');
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F376222944');
        $this->addSql('ALTER TABLE projet_utilisateur DROP FOREIGN KEY FK_C626378DC18272');
        $this->addSql('ALTER TABLE `signal` DROP FOREIGN KEY FK_740C95F576222944');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_23677143523B2018');
        $this->addSql('ALTER TABLE projet_utilisateur DROP FOREIGN KEY FK_C626378DFB88E14F');
        $this->addSql('DROP TABLE borne');
        $this->addSql('DROP TABLE bornier');
        $this->addSql('DROP TABLE cable');
        $this->addSql('DROP TABLE catalogue_modele_borniers');
        $this->addSql('DROP TABLE catalogue_modele_cables');
        $this->addSql('DROP TABLE catalogue_modele_connecteurs');
        $this->addSql('DROP TABLE catalogue_projet_borniers');
        $this->addSql('DROP TABLE catalogue_projet_cables');
        $this->addSql('DROP TABLE catalogue_projet_connecteurs');
        $this->addSql('DROP TABLE conducteur');
        $this->addSql('DROP TABLE connecteur');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE equipement');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE projet_utilisateur');
        $this->addSql('DROP TABLE `signal`');
        $this->addSql('DROP TABLE utilisateur');
    }
}
