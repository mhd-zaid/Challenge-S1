<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230708154117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_prestation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE estimate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE estimate_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prestation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category_prestation (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_77D6DFD3B03A8386 ON category_prestation (created_by_id)');
        $this->addSql('CREATE INDEX IDX_77D6DFD3896DBBDE ON category_prestation (updated_by_id)');
        $this->addSql('CREATE TABLE "customer" (id INT NOT NULL, lastname VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, is_validated BOOLEAN NOT NULL, validation_token VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09E7927C74 ON "customer" (email)');
        $this->addSql('CREATE TABLE estimate (id INT NOT NULL, client_id INT NOT NULL, title VARCHAR(255) NOT NULL, validity_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D2EA460719EB6921 ON estimate (client_id)');
        $this->addSql('CREATE TABLE estimate_product (id INT NOT NULL, estimate_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, workforce INT NOT NULL, total_ht DOUBLE PRECISION NOT NULL, total_tva DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CEF6B85785F23082 ON estimate_product (estimate_id)');
        $this->addSql('CREATE INDEX IDX_CEF6B8574584665A ON estimate_product (product_id)');
        $this->addSql('CREATE TABLE prestation (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, duration INT NOT NULL, prestation_image_name VARCHAR(255) DEFAULT NULL, technician VARCHAR(255) NOT NULL, total_ht NUMERIC(5, 2) NOT NULL, total_tva NUMERIC(5, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_51C88FADB03A8386 ON prestation (created_by_id)');
        $this->addSql('CREATE INDEX IDX_51C88FAD896DBBDE ON prestation (updated_by_id)');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, quantity INT NOT NULL, total_ht NUMERIC(5, 2) NOT NULL, total_tva NUMERIC(5, 2) NOT NULL, description VARCHAR(255) DEFAULT NULL, product_image_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04ADB03A8386 ON product (created_by_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD896DBBDE ON product (updated_by_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, is_validated BOOLEAN NOT NULL, validation_token VARCHAR(255) NOT NULL, roles JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE category_prestation ADD CONSTRAINT FK_77D6DFD3B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_prestation ADD CONSTRAINT FK_77D6DFD3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA460719EB6921 FOREIGN KEY (client_id) REFERENCES "customer" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate_product ADD CONSTRAINT FK_CEF6B85785F23082 FOREIGN KEY (estimate_id) REFERENCES estimate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate_product ADD CONSTRAINT FK_CEF6B8574584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FADB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE category_prestation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE estimate_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE estimate_product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prestation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE category_prestation DROP CONSTRAINT FK_77D6DFD3B03A8386');
        $this->addSql('ALTER TABLE category_prestation DROP CONSTRAINT FK_77D6DFD3896DBBDE');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT FK_D2EA460719EB6921');
        $this->addSql('ALTER TABLE estimate_product DROP CONSTRAINT FK_CEF6B85785F23082');
        $this->addSql('ALTER TABLE estimate_product DROP CONSTRAINT FK_CEF6B8574584665A');
        $this->addSql('ALTER TABLE prestation DROP CONSTRAINT FK_51C88FADB03A8386');
        $this->addSql('ALTER TABLE prestation DROP CONSTRAINT FK_51C88FAD896DBBDE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADB03A8386');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD896DBBDE');
        $this->addSql('DROP TABLE category_prestation');
        $this->addSql('DROP TABLE "customer"');
        $this->addSql('DROP TABLE estimate');
        $this->addSql('DROP TABLE estimate_product');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE "user"');
    }
}
