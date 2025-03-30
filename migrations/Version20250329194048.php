<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250329194048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalogue_conducteur (id INT AUTO_INCREMENT NOT NULL, catalogue_modele_cables_id INT DEFAULT NULL, catalogue_projet_cables_id INT DEFAULT NULL, attribut VARCHAR(255) DEFAULT NULL, INDEX IDX_58D1FABD2A94A665 (catalogue_modele_cables_id), INDEX IDX_58D1FABDE70CF8A3 (catalogue_projet_cables_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE catalogue_conducteur ADD CONSTRAINT FK_58D1FABD2A94A665 FOREIGN KEY (catalogue_modele_cables_id) REFERENCES catalogue_modele_cables (id)');
        $this->addSql('ALTER TABLE catalogue_conducteur ADD CONSTRAINT FK_58D1FABDE70CF8A3 FOREIGN KEY (catalogue_projet_cables_id) REFERENCES catalogue_projet_cables (id)');
        $this->addSql('ALTER TABLE catalogue_modele_cables CHANGE nombre_conducteurs_max nb_conducteurs INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE catalogue_conducteur');
        $this->addSql('ALTER TABLE catalogue_modele_cables CHANGE nb_conducteurs nombre_conducteurs_max INT NOT NULL');
    }
}
