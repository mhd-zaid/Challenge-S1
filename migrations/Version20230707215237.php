<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230707215237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ALTER city DROP NOT NULL');
        $this->addSql('ALTER TABLE customer ALTER country DROP NOT NULL');
        $this->addSql('ALTER TABLE customer ALTER zip_code TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE customer ALTER zip_code DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "customer" ALTER city SET NOT NULL');
        $this->addSql('ALTER TABLE "customer" ALTER country SET NOT NULL');
        $this->addSql('ALTER TABLE "customer" ALTER zip_code TYPE INT');
        $this->addSql('ALTER TABLE "customer" ALTER zip_code SET NOT NULL');
    }
}
