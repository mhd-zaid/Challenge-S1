<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230707211239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estimate_prestation ADD prestation_id INT NOT NULL');
        $this->addSql('ALTER TABLE estimate_prestation ALTER estimate_id SET NOT NULL');
        $this->addSql('ALTER TABLE estimate_prestation ADD CONSTRAINT FK_633C67A59E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_633C67A59E45C554 ON estimate_prestation (prestation_id)');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT fk_9065174419eb6921');
        $this->addSql('DROP INDEX idx_9065174419eb6921');
        $this->addSql('ALTER TABLE invoice RENAME COLUMN client_id TO customer_id');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517449395C3F3 FOREIGN KEY (customer_id) REFERENCES "customer" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_906517449395C3F3 ON invoice (customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE estimate_prestation DROP CONSTRAINT FK_633C67A59E45C554');
        $this->addSql('DROP INDEX IDX_633C67A59E45C554');
        $this->addSql('ALTER TABLE estimate_prestation DROP prestation_id');
        $this->addSql('ALTER TABLE estimate_prestation ALTER estimate_id DROP NOT NULL');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517449395C3F3');
        $this->addSql('DROP INDEX IDX_906517449395C3F3');
        $this->addSql('ALTER TABLE invoice RENAME COLUMN customer_id TO client_id');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT fk_9065174419eb6921 FOREIGN KEY (client_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9065174419eb6921 ON invoice (client_id)');
    }
}
