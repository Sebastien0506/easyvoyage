<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115000524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pays_image ADD pays_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pays_image ADD CONSTRAINT FK_CE837164A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('CREATE INDEX IDX_CE837164A6E44244 ON pays_image (pays_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pays_image DROP FOREIGN KEY FK_CE837164A6E44244');
        $this->addSql('DROP INDEX IDX_CE837164A6E44244 ON pays_image');
        $this->addSql('ALTER TABLE pays_image DROP pays_id');
    }
}
