<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230614145427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE prestation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE prestation (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, duration INT NOT NULL, prestation_image_name VARCHAR(255) DEFAULT NULL, technician VARCHAR(255) NOT NULL, total_ht NUMERIC(5, 2) NOT NULL, total_tva NUMERIC(5, 2) NOT NULL, total_ttc NUMERIC(5, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_51C88FADB03A8386 ON prestation (created_by_id)');
        $this->addSql('CREATE INDEX IDX_51C88FAD896DBBDE ON prestation (updated_by_id)');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FADB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE prestation_id_seq CASCADE');
        $this->addSql('ALTER TABLE prestation DROP CONSTRAINT FK_51C88FADB03A8386');
        $this->addSql('ALTER TABLE prestation DROP CONSTRAINT FK_51C88FAD896DBBDE');
        $this->addSql('DROP TABLE prestation');
    }
}
