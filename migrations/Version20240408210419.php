<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408210419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pays ADD favoris_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pays ADD CONSTRAINT FK_349F3CAE51E8871B FOREIGN KEY (favoris_id) REFERENCES favoris (id)');
        $this->addSql('CREATE INDEX IDX_349F3CAE51E8871B ON pays (favoris_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pays DROP FOREIGN KEY FK_349F3CAE51E8871B');
        $this->addSql('DROP INDEX IDX_349F3CAE51E8871B ON pays');
        $this->addSql('ALTER TABLE pays DROP favoris_id');
    }
}
