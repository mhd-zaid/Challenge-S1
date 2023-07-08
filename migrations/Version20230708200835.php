<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230708200835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE estimate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE estimate_prestation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE invoice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE invoice_prestation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prestation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prestation_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, name VARCHAR(255) NOT NULL, date_of_creation DATE NOT NULL, owner_first_name VARCHAR(255) NOT NULL, owner_last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, siret VARCHAR(255) NOT NULL, tva VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, theme VARCHAR(255) NOT NULL, company_image_name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "customer" (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, plain_password VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, is_validated BOOLEAN NOT NULL, validation_token VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, is_registered BOOLEAN NOT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, theme VARCHAR(255) DEFAULT NULL, language VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09E7927C74 ON "customer" (email)');
        $this->addSql('CREATE INDEX IDX_81398E09B03A8386 ON "customer" (created_by_id)');
        $this->addSql('CREATE INDEX IDX_81398E09896DBBDE ON "customer" (updated_by_id)');
        $this->addSql('CREATE TABLE estimate (id INT NOT NULL, customer_id INT NOT NULL, invoice_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, validity_date DATE NOT NULL, status VARCHAR(255) DEFAULT NULL, car_id VARCHAR(255) DEFAULT NULL, car_brand VARCHAR(20) DEFAULT NULL, car_model VARCHAR(50) DEFAULT NULL, car_type VARCHAR(15) DEFAULT NULL, uuid_success_payment TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D2EA46079395C3F3 ON estimate (customer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D2EA46072989F1FD ON estimate (invoice_id)');
        $this->addSql('CREATE INDEX IDX_D2EA4607B03A8386 ON estimate (created_by_id)');
        $this->addSql('CREATE INDEX IDX_D2EA4607896DBBDE ON estimate (updated_by_id)');
        $this->addSql('CREATE TABLE estimate_prestation (id INT NOT NULL, estimate_id INT NOT NULL, prestation_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_633C67A585F23082 ON estimate_prestation (estimate_id)');
        $this->addSql('CREATE INDEX IDX_633C67A59E45C554 ON estimate_prestation (prestation_id)');
        $this->addSql('CREATE TABLE invoice (id INT NOT NULL, customer_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_906517449395C3F3 ON invoice (customer_id)');
        $this->addSql('CREATE INDEX IDX_90651744B03A8386 ON invoice (created_by_id)');
        $this->addSql('CREATE INDEX IDX_90651744896DBBDE ON invoice (updated_by_id)');
        $this->addSql('CREATE TABLE invoice_prestation (id INT NOT NULL, invoice_id INT NOT NULL, prestation_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A814FF332989F1FD ON invoice_prestation (invoice_id)');
        $this->addSql('CREATE INDEX IDX_A814FF339E45C554 ON invoice_prestation (prestation_id)');
        $this->addSql('CREATE TABLE prestation (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, duration INT NOT NULL, workforce INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_51C88FADB03A8386 ON prestation (created_by_id)');
        $this->addSql('CREATE INDEX IDX_51C88FAD896DBBDE ON prestation (updated_by_id)');
        $this->addSql('CREATE TABLE prestation_product (id INT NOT NULL, prestation_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, workforce INT NOT NULL, total_ht DOUBLE PRECISION NOT NULL, total_tva DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_10DCF7349E45C554 ON prestation_product (prestation_id)');
        $this->addSql('CREATE INDEX IDX_10DCF7344584665A ON prestation_product (product_id)');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, quantity INT NOT NULL, total_ht NUMERIC(5, 2) NOT NULL, total_tva NUMERIC(5, 2) NOT NULL, description VARCHAR(255) DEFAULT NULL, product_image_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04ADB03A8386 ON product (created_by_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD896DBBDE ON product (updated_by_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, is_validated BOOLEAN NOT NULL, validation_token VARCHAR(255) NOT NULL, roles JSON NOT NULL, phone VARCHAR(25) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, theme VARCHAR(255) DEFAULT NULL, language VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE "customer" ADD CONSTRAINT FK_81398E09B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "customer" ADD CONSTRAINT FK_81398E09896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA46079395C3F3 FOREIGN KEY (customer_id) REFERENCES "customer" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA46072989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA4607B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA4607896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate_prestation ADD CONSTRAINT FK_633C67A585F23082 FOREIGN KEY (estimate_id) REFERENCES estimate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate_prestation ADD CONSTRAINT FK_633C67A59E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517449395C3F3 FOREIGN KEY (customer_id) REFERENCES "customer" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_prestation ADD CONSTRAINT FK_A814FF332989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_prestation ADD CONSTRAINT FK_A814FF339E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FADB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prestation_product ADD CONSTRAINT FK_10DCF7349E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prestation_product ADD CONSTRAINT FK_10DCF7344584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE estimate_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE estimate_prestation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE invoice_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE invoice_prestation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prestation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prestation_product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "customer" DROP CONSTRAINT FK_81398E09B03A8386');
        $this->addSql('ALTER TABLE "customer" DROP CONSTRAINT FK_81398E09896DBBDE');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT FK_D2EA46079395C3F3');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT FK_D2EA46072989F1FD');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT FK_D2EA4607B03A8386');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT FK_D2EA4607896DBBDE');
        $this->addSql('ALTER TABLE estimate_prestation DROP CONSTRAINT FK_633C67A585F23082');
        $this->addSql('ALTER TABLE estimate_prestation DROP CONSTRAINT FK_633C67A59E45C554');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517449395C3F3');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744B03A8386');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744896DBBDE');
        $this->addSql('ALTER TABLE invoice_prestation DROP CONSTRAINT FK_A814FF332989F1FD');
        $this->addSql('ALTER TABLE invoice_prestation DROP CONSTRAINT FK_A814FF339E45C554');
        $this->addSql('ALTER TABLE prestation DROP CONSTRAINT FK_51C88FADB03A8386');
        $this->addSql('ALTER TABLE prestation DROP CONSTRAINT FK_51C88FAD896DBBDE');
        $this->addSql('ALTER TABLE prestation_product DROP CONSTRAINT FK_10DCF7349E45C554');
        $this->addSql('ALTER TABLE prestation_product DROP CONSTRAINT FK_10DCF7344584665A');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADB03A8386');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04AD896DBBDE');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE "customer"');
        $this->addSql('DROP TABLE estimate');
        $this->addSql('DROP TABLE estimate_prestation');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_prestation');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE prestation_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE "user"');
    }
}
