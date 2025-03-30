<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330133250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalogue_projet_borniers DROP FOREIGN KEY FK_D1093D9B76222944');
        $this->addSql('DROP INDEX IDX_D1093D9B76222944 ON catalogue_projet_borniers');
        $this->addSql('ALTER TABLE catalogue_projet_borniers CHANGE id_projet projet_id INT NOT NULL');
        $this->addSql('ALTER TABLE catalogue_projet_borniers ADD CONSTRAINT FK_D1093D9BC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('CREATE INDEX IDX_D1093D9BC18272 ON catalogue_projet_borniers (projet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalogue_projet_borniers DROP FOREIGN KEY FK_D1093D9BC18272');
        $this->addSql('DROP INDEX IDX_D1093D9BC18272 ON catalogue_projet_borniers');
        $this->addSql('ALTER TABLE catalogue_projet_borniers CHANGE projet_id id_projet INT NOT NULL');
        $this->addSql('ALTER TABLE catalogue_projet_borniers ADD CONSTRAINT FK_D1093D9B76222944 FOREIGN KEY (id_projet) REFERENCES projet (id)');
        $this->addSql('CREATE INDEX IDX_D1093D9B76222944 ON catalogue_projet_borniers (id_projet)');
    }
}
