<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230708212621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestation ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE prestation DROP category');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_51C88FAD12469DE2 ON prestation (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE prestation DROP CONSTRAINT FK_51C88FAD12469DE2');
        $this->addSql('DROP INDEX IDX_51C88FAD12469DE2');
        $this->addSql('ALTER TABLE prestation ADD category VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE prestation DROP category_id');
    }
}
