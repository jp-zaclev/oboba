<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330132907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalogue_borne (id INT AUTO_INCREMENT NOT NULL, catalogue_modele_borniers_id INT DEFAULT NULL, catalogue_projet_borniers_id INT DEFAULT NULL, attribut VARCHAR(255) DEFAULT NULL, INDEX IDX_CF5A29149540A349 (catalogue_modele_borniers_id), INDEX IDX_CF5A2914843362EA (catalogue_projet_borniers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE catalogue_borne ADD CONSTRAINT FK_CF5A29149540A349 FOREIGN KEY (catalogue_modele_borniers_id) REFERENCES catalogue_modele_borniers (id)');
        $this->addSql('ALTER TABLE catalogue_borne ADD CONSTRAINT FK_CF5A2914843362EA FOREIGN KEY (catalogue_projet_borniers_id) REFERENCES catalogue_projet_borniers (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE catalogue_borne');
    }
}
