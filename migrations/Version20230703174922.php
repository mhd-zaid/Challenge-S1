<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703174922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estimate ADD invoice_id INT NOT NULL');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA46072989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D2EA46072989F1FD ON estimate (invoice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE estimate DROP CONSTRAINT FK_D2EA46072989F1FD');
        $this->addSql('DROP INDEX UNIQ_D2EA46072989F1FD');
        $this->addSql('ALTER TABLE estimate DROP invoice_id');
    }
}
