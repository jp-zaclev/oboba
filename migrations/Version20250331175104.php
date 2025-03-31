<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331175104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalogue_contact ADD catalogue_projet_connecteurs_id INT DEFAULT NULL, CHANGE catalogue_modele_connecteurs_id catalogue_modele_connecteurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE catalogue_contact ADD CONSTRAINT FK_FB771CD69BC6F68 FOREIGN KEY (catalogue_projet_connecteurs_id) REFERENCES catalogue_projet_connecteurs (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_FB771CD69BC6F68 ON catalogue_contact (catalogue_projet_connecteurs_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalogue_contact DROP FOREIGN KEY FK_FB771CD69BC6F68');
        $this->addSql('DROP INDEX IDX_FB771CD69BC6F68 ON catalogue_contact');
        $this->addSql('ALTER TABLE catalogue_contact DROP catalogue_projet_connecteurs_id, CHANGE catalogue_modele_connecteurs_id catalogue_modele_connecteurs_id INT NOT NULL');
    }
}
