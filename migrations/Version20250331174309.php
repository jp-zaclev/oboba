<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331174309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_nom ON catalogue_modele_connecteurs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EACE340D6C6E55B5 ON catalogue_modele_connecteurs (nom)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_eace340d6c6e55b5 ON catalogue_modele_connecteurs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_NOM ON catalogue_modele_connecteurs (nom)');
    }
}
