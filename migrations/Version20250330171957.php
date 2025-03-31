<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330171957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalogue_contact (id INT AUTO_INCREMENT NOT NULL, catalogue_modele_connecteurs_id INT NOT NULL, identifiant VARCHAR(50) NOT NULL, type VARCHAR(20) NOT NULL, INDEX IDX_FB771CDA96F4AC9 (catalogue_modele_connecteurs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE catalogue_contact ADD CONSTRAINT FK_FB771CDA96F4AC9 FOREIGN KEY (catalogue_modele_connecteurs_id) REFERENCES catalogue_modele_connecteurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE catalogue_borne DROP FOREIGN KEY FK_CF5A29149540A349');
        $this->addSql('ALTER TABLE catalogue_borne ADD CONSTRAINT FK_CF5A29149540A349 FOREIGN KEY (catalogue_modele_borniers_id) REFERENCES catalogue_modele_borniers (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE catalogue_contact');
        $this->addSql('ALTER TABLE catalogue_borne DROP FOREIGN KEY FK_CF5A29149540A349');
        $this->addSql('ALTER TABLE catalogue_borne ADD CONSTRAINT FK_CF5A29149540A349 FOREIGN KEY (catalogue_modele_borniers_id) REFERENCES catalogue_modele_borniers (id)');
    }
}
