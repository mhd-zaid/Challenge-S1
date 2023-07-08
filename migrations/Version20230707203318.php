<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230707203318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, name VARCHAR(255) NOT NULL, date_of_creation DATE NOT NULL, owner_first_name VARCHAR(255) NOT NULL, owner_last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, siret VARCHAR(255) NOT NULL, tva VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, theme VARCHAR(255) NOT NULL, company_image_name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE customer ADD city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE customer ADD country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE customer ADD zip_code INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP TABLE company');
        $this->addSql('ALTER TABLE "customer" DROP city');
        $this->addSql('ALTER TABLE "customer" DROP country');
        $this->addSql('ALTER TABLE "customer" DROP zip_code');
    }
}
