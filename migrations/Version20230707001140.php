<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230707001140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE estimate_product_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE estimate_prestation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE prestation_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE estimate_prestation (id INT NOT NULL, estimate_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_633C67A585F23082 ON estimate_prestation (estimate_id)');
        $this->addSql('CREATE TABLE prestation_product (id INT NOT NULL, prestation_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, workforce INT NOT NULL, total_ht DOUBLE PRECISION NOT NULL, total_tva DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_10DCF7349E45C554 ON prestation_product (prestation_id)');
        $this->addSql('CREATE INDEX IDX_10DCF7344584665A ON prestation_product (product_id)');
        $this->addSql('ALTER TABLE estimate_prestation ADD CONSTRAINT FK_633C67A585F23082 FOREIGN KEY (estimate_id) REFERENCES estimate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prestation_product ADD CONSTRAINT FK_10DCF7349E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prestation_product ADD CONSTRAINT FK_10DCF7344584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate_product DROP CONSTRAINT fk_cef6b85785f23082');
        $this->addSql('ALTER TABLE estimate_product DROP CONSTRAINT fk_cef6b8574584665a');
        $this->addSql('DROP TABLE estimate_product');
        $this->addSql('ALTER TABLE customer ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_81398E09B03A8386 ON customer (created_by_id)');
        $this->addSql('CREATE INDEX IDX_81398E09896DBBDE ON customer (updated_by_id)');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT fk_d2ea460719eb6921');
        $this->addSql('DROP INDEX idx_d2ea460719eb6921');
        $this->addSql('ALTER TABLE estimate ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE estimate ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE estimate ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE estimate ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE estimate RENAME COLUMN client_id TO customer_id');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA46079395C3F3 FOREIGN KEY (customer_id) REFERENCES "customer" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA4607B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA4607896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D2EA46079395C3F3 ON estimate (customer_id)');
        $this->addSql('CREATE INDEX IDX_D2EA4607B03A8386 ON estimate (created_by_id)');
        $this->addSql('CREATE INDEX IDX_D2EA4607896DBBDE ON estimate (updated_by_id)');
        $this->addSql('ALTER TABLE invoice ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_90651744B03A8386 ON invoice (created_by_id)');
        $this->addSql('CREATE INDEX IDX_90651744896DBBDE ON invoice (updated_by_id)');
        $this->addSql('ALTER TABLE invoice_product ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice_product ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice_product ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE invoice_product ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE invoice_product ADD CONSTRAINT FK_2193327EB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice_product ADD CONSTRAINT FK_2193327E896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2193327EB03A8386 ON invoice_product (created_by_id)');
        $this->addSql('CREATE INDEX IDX_2193327E896DBBDE ON invoice_product (updated_by_id)');
        $this->addSql('ALTER TABLE prestation ADD workforce INT NOT NULL');
        $this->addSql('ALTER TABLE prestation DROP prestation_image_name');
        $this->addSql('ALTER TABLE prestation DROP technician');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE estimate_prestation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE prestation_product_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE estimate_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE estimate_product (id INT NOT NULL, estimate_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, workforce INT NOT NULL, total_ht DOUBLE PRECISION NOT NULL, total_tva DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_cef6b8574584665a ON estimate_product (product_id)');
        $this->addSql('CREATE INDEX idx_cef6b85785f23082 ON estimate_product (estimate_id)');
        $this->addSql('ALTER TABLE estimate_product ADD CONSTRAINT fk_cef6b85785f23082 FOREIGN KEY (estimate_id) REFERENCES estimate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate_product ADD CONSTRAINT fk_cef6b8574584665a FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE estimate_prestation DROP CONSTRAINT FK_633C67A585F23082');
        $this->addSql('ALTER TABLE prestation_product DROP CONSTRAINT FK_10DCF7349E45C554');
        $this->addSql('ALTER TABLE prestation_product DROP CONSTRAINT FK_10DCF7344584665A');
        $this->addSql('DROP TABLE estimate_prestation');
        $this->addSql('DROP TABLE prestation_product');
        $this->addSql('ALTER TABLE "customer" DROP CONSTRAINT FK_81398E09B03A8386');
        $this->addSql('ALTER TABLE "customer" DROP CONSTRAINT FK_81398E09896DBBDE');
        $this->addSql('DROP INDEX IDX_81398E09B03A8386');
        $this->addSql('DROP INDEX IDX_81398E09896DBBDE');
        $this->addSql('ALTER TABLE "customer" DROP created_by_id');
        $this->addSql('ALTER TABLE "customer" DROP updated_by_id');
        $this->addSql('ALTER TABLE prestation ADD prestation_image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE prestation ADD technician VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE prestation DROP workforce');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT FK_D2EA46079395C3F3');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT FK_D2EA4607B03A8386');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT FK_D2EA4607896DBBDE');
        $this->addSql('DROP INDEX IDX_D2EA46079395C3F3');
        $this->addSql('DROP INDEX IDX_D2EA4607B03A8386');
        $this->addSql('DROP INDEX IDX_D2EA4607896DBBDE');
        $this->addSql('ALTER TABLE estimate DROP created_by_id');
        $this->addSql('ALTER TABLE estimate DROP updated_by_id');
        $this->addSql('ALTER TABLE estimate DROP created_at');
        $this->addSql('ALTER TABLE estimate DROP updated_at');
        $this->addSql('ALTER TABLE estimate RENAME COLUMN customer_id TO client_id');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT fk_d2ea460719eb6921 FOREIGN KEY (client_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d2ea460719eb6921 ON estimate (client_id)');
        $this->addSql('ALTER TABLE invoice_product DROP CONSTRAINT FK_2193327EB03A8386');
        $this->addSql('ALTER TABLE invoice_product DROP CONSTRAINT FK_2193327E896DBBDE');
        $this->addSql('DROP INDEX IDX_2193327EB03A8386');
        $this->addSql('DROP INDEX IDX_2193327E896DBBDE');
        $this->addSql('ALTER TABLE invoice_product DROP created_by_id');
        $this->addSql('ALTER TABLE invoice_product DROP updated_by_id');
        $this->addSql('ALTER TABLE invoice_product DROP created_at');
        $this->addSql('ALTER TABLE invoice_product DROP updated_at');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744B03A8386');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744896DBBDE');
        $this->addSql('DROP INDEX IDX_90651744B03A8386');
        $this->addSql('DROP INDEX IDX_90651744896DBBDE');
        $this->addSql('ALTER TABLE invoice DROP created_by_id');
        $this->addSql('ALTER TABLE invoice DROP updated_by_id');
        $this->addSql('ALTER TABLE invoice DROP created_at');
        $this->addSql('ALTER TABLE invoice DROP updated_at');
    }
}
