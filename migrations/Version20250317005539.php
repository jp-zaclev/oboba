<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250317005539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalogue_projet_connecteurs (id INT AUTO_INCREMENT NOT NULL, id_projet INT NOT NULL, nom VARCHAR(255) NOT NULL, nombre_contacts INT NOT NULL, type VARCHAR(50) NOT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, INDEX IDX_FBBDF5AE76222944 (id_projet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE connecteur (id INT AUTO_INCREMENT NOT NULL, id_catalogue_projet_connecteur INT NOT NULL, id_projet INT NOT NULL, id_equipement INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, localisation VARCHAR(255) DEFAULT NULL, INDEX IDX_84C12C9691B692E (id_catalogue_projet_connecteur), INDEX IDX_84C12C9676222944 (id_projet), INDEX IDX_84C12C961D3E4624 (id_equipement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, id_connecteur INT NOT NULL, identifiant VARCHAR(50) NOT NULL, type ENUM(\'emission\', \'reception\', \'emission_reception\'), INDEX IDX_4C62E638214BAC41 (id_connecteur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipement (id INT AUTO_INCREMENT NOT NULL, id_projet INT NOT NULL, nom VARCHAR(255) NOT NULL, reference VARCHAR(50) NOT NULL, INDEX IDX_B8B4C6F376222944 (id_projet), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE catalogue_projet_connecteurs ADD CONSTRAINT FK_FBBDF5AE76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE connecteur ADD CONSTRAINT FK_84C12C9691B692E FOREIGN KEY (id_catalogue_projet_connecteur) REFERENCES catalogue_projet_connecteurs (id)');
        $this->addSql('ALTER TABLE connecteur ADD CONSTRAINT FK_84C12C9676222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE connecteur ADD CONSTRAINT FK_84C12C961D3E4624 FOREIGN KEY (id_equipement) REFERENCES equipement (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638214BAC41 FOREIGN KEY (id_connecteur) REFERENCES connecteur (id)');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F376222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE borne DROP FOREIGN KEY borne_ibfk_1');
        $this->addSql('ALTER TABLE borne DROP FOREIGN KEY borne_ibfk_1');
        $this->addSql('ALTER TABLE borne ADD CONSTRAINT FK_D7465BA51F16DA8F FOREIGN KEY (id_bornier) REFERENCES bornier (id)');
        $this->addSql('DROP INDEX id_bornier ON borne');
        $this->addSql('CREATE INDEX IDX_D7465BA51F16DA8F ON borne (id_bornier)');
        $this->addSql('ALTER TABLE borne ADD CONSTRAINT borne_ibfk_1 FOREIGN KEY (id_bornier) REFERENCES bornier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bornier DROP FOREIGN KEY bornier_ibfk_1');
        $this->addSql('ALTER TABLE bornier DROP FOREIGN KEY bornier_ibfk_2');
        $this->addSql('ALTER TABLE bornier CHANGE localisation localisation VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX id_catalogue_projet_bornier ON bornier');
        $this->addSql('CREATE INDEX IDX_C18B73FFD63520D2 ON bornier (id_catalogue_projet_bornier)');
        $this->addSql('DROP INDEX id_projet ON bornier');
        $this->addSql('CREATE INDEX IDX_C18B73FF76222944 ON bornier (id_projet)');
        $this->addSql('DROP INDEX uniq_nom_projet ON bornier');
        $this->addSql('CREATE UNIQUE INDEX nom_projet_unique ON bornier (nom, id_projet)');
        $this->addSql('ALTER TABLE bornier ADD CONSTRAINT bornier_ibfk_1 FOREIGN KEY (id_catalogue_projet_bornier) REFERENCES catalogue_projet_borniers (id)');
        $this->addSql('ALTER TABLE bornier ADD CONSTRAINT bornier_ibfk_2 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE cable DROP FOREIGN KEY cable_ibfk_1');
        $this->addSql('ALTER TABLE cable DROP FOREIGN KEY cable_ibfk_2');
        $this->addSql('DROP INDEX id_catalogue_projet_cable ON cable');
        $this->addSql('CREATE INDEX IDX_24E9C4D47FCB71D8 ON cable (id_catalogue_projet_cable)');
        $this->addSql('DROP INDEX id_projet ON cable');
        $this->addSql('CREATE INDEX IDX_24E9C4D476222944 ON cable (id_projet)');
        $this->addSql('DROP INDEX uniq_nom_projet ON cable');
        $this->addSql('CREATE UNIQUE INDEX nom_projet_unique ON cable (nom, id_projet)');
        $this->addSql('ALTER TABLE cable ADD CONSTRAINT cable_ibfk_1 FOREIGN KEY (id_catalogue_projet_cable) REFERENCES catalogue_projet_cables (id)');
        $this->addSql('ALTER TABLE cable ADD CONSTRAINT cable_ibfk_2 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9E51686E6C6E55B5 ON catalogue_modele_borniers (nom)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5216F85D6C6E55B5 ON catalogue_modele_cables (nom)');
        $this->addSql('ALTER TABLE catalogue_modele_connecteurs CHANGE prix_unitaire prix_unitaire NUMERIC(10, 2) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EACE340D6C6E55B5 ON catalogue_modele_connecteurs (nom)');
        $this->addSql('ALTER TABLE catalogue_projet_borniers DROP FOREIGN KEY catalogue_projet_borniers_ibfk_1');
        $this->addSql('DROP INDEX id_projet ON catalogue_projet_borniers');
        $this->addSql('CREATE INDEX IDX_D1093D9B76222944 ON catalogue_projet_borniers (id_projet)');
        $this->addSql('ALTER TABLE catalogue_projet_borniers ADD CONSTRAINT catalogue_projet_borniers_ibfk_1 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE catalogue_projet_cables DROP FOREIGN KEY catalogue_projet_cables_ibfk_1');
        $this->addSql('DROP INDEX id_projet ON catalogue_projet_cables');
        $this->addSql('CREATE INDEX IDX_FD1289CC76222944 ON catalogue_projet_cables (id_projet)');
        $this->addSql('ALTER TABLE catalogue_projet_cables ADD CONSTRAINT catalogue_projet_cables_ibfk_1 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY conducteur_ibfk_1');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY conducteur_ibfk_1');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY conducteur_ibfk_2');
        $this->addSql('ALTER TABLE conducteur ADD id_borne_source INT DEFAULT NULL, ADD id_borne_destination INT DEFAULT NULL, ADD id_contact_source INT DEFAULT NULL, ADD id_contact_destination INT DEFAULT NULL, DROP id_extremite_source, DROP id_extremite_destination');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_23677143CA6C85E4 FOREIGN KEY (id_cable) REFERENCES cable (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_236771439A92C5B6 FOREIGN KEY (id_borne_source) REFERENCES borne (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_23677143C944263C FOREIGN KEY (id_borne_destination) REFERENCES borne (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_23677143F27EF5F4 FOREIGN KEY (id_contact_source) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_2367714316801DDF FOREIGN KEY (id_contact_destination) REFERENCES contact (id)');
        $this->addSql('CREATE INDEX IDX_236771439A92C5B6 ON conducteur (id_borne_source)');
        $this->addSql('CREATE INDEX IDX_23677143C944263C ON conducteur (id_borne_destination)');
        $this->addSql('CREATE INDEX IDX_23677143F27EF5F4 ON conducteur (id_contact_source)');
        $this->addSql('CREATE INDEX IDX_2367714316801DDF ON conducteur (id_contact_destination)');
        $this->addSql('DROP INDEX id_cable ON conducteur');
        $this->addSql('CREATE INDEX IDX_23677143CA6C85E4 ON conducteur (id_cable)');
        $this->addSql('DROP INDEX id_signal ON conducteur');
        $this->addSql('CREATE INDEX IDX_23677143523B2018 ON conducteur (id_signal)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT conducteur_ibfk_1 FOREIGN KEY (id_cable) REFERENCES cable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT conducteur_ibfk_2 FOREIGN KEY (id_signal) REFERENCES `signal` (id)');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY projet_ibfk_1');
        $this->addSql('DROP INDEX id_utilisateur_proprietaire ON projet');
        $this->addSql('ALTER TABLE projet DROP id_utilisateur_proprietaire, CHANGE date_heure_creation date_heure_creation DATETIME NOT NULL, CHANGE date_heure_derniere_modification date_heure_derniere_modification DATETIME NOT NULL');
        $this->addSql('ALTER TABLE projet_utilisateur DROP FOREIGN KEY projet_utilisateur_ibfk_1');
        $this->addSql('ALTER TABLE projet_utilisateur DROP FOREIGN KEY projet_utilisateur_ibfk_2');
        $this->addSql('DROP INDEX id_utilisateur ON projet_utilisateur');
        $this->addSql('DROP INDEX IDX_C626378D76222944 ON projet_utilisateur');
        $this->addSql('ALTER TABLE projet_utilisateur ADD id INT AUTO_INCREMENT NOT NULL, ADD projet_id INT NOT NULL, ADD utilisateur_id INT NOT NULL, DROP id_projet, DROP id_utilisateur, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE projet_utilisateur ADD CONSTRAINT FK_C626378DC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE projet_utilisateur ADD CONSTRAINT FK_C626378DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_C626378DC18272 ON projet_utilisateur (projet_id)');
        $this->addSql('CREATE INDEX IDX_C626378DFB88E14F ON projet_utilisateur (utilisateur_id)');
        $this->addSql('ALTER TABLE `signal` DROP FOREIGN KEY signal_ibfk_1');
        $this->addSql('ALTER TABLE `signal` CHANGE nom nom VARCHAR(50) NOT NULL');
        $this->addSql('DROP INDEX nom ON `signal`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_740C95F56C6E55B5 ON `signal` (nom)');
        $this->addSql('DROP INDEX id_projet ON `signal`');
        $this->addSql('CREATE INDEX IDX_740C95F576222944 ON `signal` (id_projet)');
        $this->addSql('ALTER TABLE `signal` ADD CONSTRAINT signal_ibfk_1 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE utilisateur ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE mot_de_passe password VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX email ON utilisateur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE connecteur DROP FOREIGN KEY FK_84C12C9691B692E');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638214BAC41');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_23677143F27EF5F4');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_2367714316801DDF');
        $this->addSql('ALTER TABLE connecteur DROP FOREIGN KEY FK_84C12C961D3E4624');
        $this->addSql('DROP TABLE catalogue_projet_connecteurs');
        $this->addSql('DROP TABLE connecteur');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE equipement');
        $this->addSql('ALTER TABLE borne DROP FOREIGN KEY FK_D7465BA51F16DA8F');
        $this->addSql('ALTER TABLE borne DROP FOREIGN KEY FK_D7465BA51F16DA8F');
        $this->addSql('ALTER TABLE borne ADD CONSTRAINT borne_ibfk_1 FOREIGN KEY (id_bornier) REFERENCES bornier (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_d7465ba51f16da8f ON borne');
        $this->addSql('CREATE INDEX id_bornier ON borne (id_bornier)');
        $this->addSql('ALTER TABLE borne ADD CONSTRAINT FK_D7465BA51F16DA8F FOREIGN KEY (id_bornier) REFERENCES bornier (id)');
        $this->addSql('ALTER TABLE bornier DROP FOREIGN KEY FK_C18B73FFD63520D2');
        $this->addSql('ALTER TABLE bornier DROP FOREIGN KEY FK_C18B73FF76222944');
        $this->addSql('ALTER TABLE bornier CHANGE localisation localisation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('DROP INDEX idx_c18b73ffd63520d2 ON bornier');
        $this->addSql('CREATE INDEX id_catalogue_projet_bornier ON bornier (id_catalogue_projet_bornier)');
        $this->addSql('DROP INDEX idx_c18b73ff76222944 ON bornier');
        $this->addSql('CREATE INDEX id_projet ON bornier (id_projet)');
        $this->addSql('DROP INDEX nom_projet_unique ON bornier');
        $this->addSql('CREATE UNIQUE INDEX uniq_nom_projet ON bornier (nom, id_projet)');
        $this->addSql('ALTER TABLE bornier ADD CONSTRAINT FK_C18B73FFD63520D2 FOREIGN KEY (id_catalogue_projet_bornier) REFERENCES catalogue_projet_borniers (id)');
        $this->addSql('ALTER TABLE bornier ADD CONSTRAINT FK_C18B73FF76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE cable DROP FOREIGN KEY FK_24E9C4D47FCB71D8');
        $this->addSql('ALTER TABLE cable DROP FOREIGN KEY FK_24E9C4D476222944');
        $this->addSql('DROP INDEX idx_24e9c4d47fcb71d8 ON cable');
        $this->addSql('CREATE INDEX id_catalogue_projet_cable ON cable (id_catalogue_projet_cable)');
        $this->addSql('DROP INDEX idx_24e9c4d476222944 ON cable');
        $this->addSql('CREATE INDEX id_projet ON cable (id_projet)');
        $this->addSql('DROP INDEX nom_projet_unique ON cable');
        $this->addSql('CREATE UNIQUE INDEX uniq_nom_projet ON cable (nom, id_projet)');
        $this->addSql('ALTER TABLE cable ADD CONSTRAINT FK_24E9C4D47FCB71D8 FOREIGN KEY (id_catalogue_projet_cable) REFERENCES catalogue_projet_cables (id)');
        $this->addSql('ALTER TABLE cable ADD CONSTRAINT FK_24E9C4D476222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('DROP INDEX UNIQ_9E51686E6C6E55B5 ON catalogue_modele_borniers');
        $this->addSql('DROP INDEX UNIQ_5216F85D6C6E55B5 ON catalogue_modele_cables');
        $this->addSql('DROP INDEX UNIQ_EACE340D6C6E55B5 ON catalogue_modele_connecteurs');
        $this->addSql('ALTER TABLE catalogue_modele_connecteurs CHANGE prix_unitaire prix_unitaire NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE catalogue_projet_borniers DROP FOREIGN KEY FK_D1093D9B76222944');
        $this->addSql('DROP INDEX idx_d1093d9b76222944 ON catalogue_projet_borniers');
        $this->addSql('CREATE INDEX id_projet ON catalogue_projet_borniers (id_projet)');
        $this->addSql('ALTER TABLE catalogue_projet_borniers ADD CONSTRAINT FK_D1093D9B76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE catalogue_projet_cables DROP FOREIGN KEY FK_FD1289CC76222944');
        $this->addSql('DROP INDEX idx_fd1289cc76222944 ON catalogue_projet_cables');
        $this->addSql('CREATE INDEX id_projet ON catalogue_projet_cables (id_projet)');
        $this->addSql('ALTER TABLE catalogue_projet_cables ADD CONSTRAINT FK_FD1289CC76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_23677143CA6C85E4');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_236771439A92C5B6');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_23677143C944263C');
        $this->addSql('DROP INDEX IDX_236771439A92C5B6 ON conducteur');
        $this->addSql('DROP INDEX IDX_23677143C944263C ON conducteur');
        $this->addSql('DROP INDEX IDX_23677143F27EF5F4 ON conducteur');
        $this->addSql('DROP INDEX IDX_2367714316801DDF ON conducteur');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_23677143CA6C85E4');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_23677143523B2018');
        $this->addSql('ALTER TABLE conducteur ADD id_extremite_source INT DEFAULT NULL, ADD id_extremite_destination INT DEFAULT NULL, DROP id_borne_source, DROP id_borne_destination, DROP id_contact_source, DROP id_contact_destination');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT conducteur_ibfk_1 FOREIGN KEY (id_cable) REFERENCES cable (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_23677143523b2018 ON conducteur');
        $this->addSql('CREATE INDEX id_signal ON conducteur (id_signal)');
        $this->addSql('DROP INDEX idx_23677143ca6c85e4 ON conducteur');
        $this->addSql('CREATE INDEX id_cable ON conducteur (id_cable)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_23677143CA6C85E4 FOREIGN KEY (id_cable) REFERENCES cable (id)');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_23677143523B2018 FOREIGN KEY (id_signal) REFERENCES `signal` (id)');
        $this->addSql('ALTER TABLE projet ADD id_utilisateur_proprietaire INT NOT NULL, CHANGE date_heure_creation date_heure_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE date_heure_derniere_modification date_heure_derniere_modification DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT projet_ibfk_1 FOREIGN KEY (id_utilisateur_proprietaire) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX id_utilisateur_proprietaire ON projet (id_utilisateur_proprietaire)');
        $this->addSql('ALTER TABLE projet_utilisateur MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE projet_utilisateur DROP FOREIGN KEY FK_C626378DC18272');
        $this->addSql('ALTER TABLE projet_utilisateur DROP FOREIGN KEY FK_C626378DFB88E14F');
        $this->addSql('DROP INDEX IDX_C626378DC18272 ON projet_utilisateur');
        $this->addSql('DROP INDEX IDX_C626378DFB88E14F ON projet_utilisateur');
        $this->addSql('ALTER TABLE projet_utilisateur DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE projet_utilisateur ADD id_projet INT NOT NULL, ADD id_utilisateur INT NOT NULL, DROP id, DROP projet_id, DROP utilisateur_id');
        $this->addSql('ALTER TABLE projet_utilisateur ADD CONSTRAINT projet_utilisateur_ibfk_1 FOREIGN KEY (id_projet) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_utilisateur ADD CONSTRAINT projet_utilisateur_ibfk_2 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX id_utilisateur ON projet_utilisateur (id_utilisateur)');
        $this->addSql('CREATE INDEX IDX_C626378D76222944 ON projet_utilisateur (id_projet)');
        $this->addSql('ALTER TABLE projet_utilisateur ADD PRIMARY KEY (id_projet, id_utilisateur)');
        $this->addSql('ALTER TABLE `signal` DROP FOREIGN KEY FK_740C95F576222944');
        $this->addSql('ALTER TABLE `signal` CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('DROP INDEX idx_740c95f576222944 ON `signal`');
        $this->addSql('CREATE INDEX id_projet ON `signal` (id_projet)');
        $this->addSql('DROP INDEX uniq_740c95f56c6e55b5 ON `signal`');
        $this->addSql('CREATE UNIQUE INDEX nom ON `signal` (nom)');
        $this->addSql('ALTER TABLE `signal` ADD CONSTRAINT FK_740C95F576222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE utilisateur DROP roles, CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE password mot_de_passe VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('DROP INDEX uniq_1d1c63b3e7927c74 ON utilisateur');
        $this->addSql('CREATE UNIQUE INDEX email ON utilisateur (email)');
    }
}
