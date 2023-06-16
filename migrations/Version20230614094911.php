<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230614094911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE estimate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE estimate_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE estimate (id INT NOT NULL, client_id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D2EA460719EB6921 ON estimate (client_id)');
        $this->addSql('CREATE TABLE estimate_product (id INT NOT NULL, estimate_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CEF6B85785F23082 ON estimate_product (estimate_id)');
        $this->addSql('CREATE INDEX IDX_CEF6B8574584665A ON estimate_product (product_id)');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA460719EB6921 FOREIGN KEY (client_id) REFERENCES "customer" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate_product ADD CONSTRAINT FK_CEF6B85785F23082 FOREIGN KEY (estimate_id) REFERENCES estimate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate_product ADD CONSTRAINT FK_CEF6B8574584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE estimate_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE estimate_product_id_seq CASCADE');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT FK_D2EA460719EB6921');
        $this->addSql('ALTER TABLE estimate_product DROP CONSTRAINT FK_CEF6B85785F23082');
        $this->addSql('ALTER TABLE estimate_product DROP CONSTRAINT FK_CEF6B8574584665A');
        $this->addSql('DROP TABLE estimate');
        $this->addSql('DROP TABLE estimate_product');
    }
}
