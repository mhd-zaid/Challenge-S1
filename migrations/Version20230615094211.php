<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615094211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD total_ht NUMERIC(5, 2) NOT NULL');
        $this->addSql('ALTER TABLE product ADD total_tva NUMERIC(5, 2) NOT NULL');
        $this->addSql('ALTER TABLE product DROP price');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product ADD price INT NOT NULL');
        $this->addSql('ALTER TABLE product DROP total_ht');
        $this->addSql('ALTER TABLE product DROP total_tva');
    }
}
