<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230614123222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estimate_product ADD price INT NOT NULL');
        $this->addSql('ALTER TABLE estimate_product ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE estimate_product ADD workforce INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE estimate_product DROP price');
        $this->addSql('ALTER TABLE estimate_product DROP quantity');
        $this->addSql('ALTER TABLE estimate_product DROP workforce');
    }
}
