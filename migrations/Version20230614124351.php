<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230614124351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_prestation ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category_prestation ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category_prestation ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE category_prestation ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE category_prestation ADD CONSTRAINT FK_77D6DFD3B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_prestation ADD CONSTRAINT FK_77D6DFD3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_77D6DFD3B03A8386 ON category_prestation (created_by_id)');
        $this->addSql('CREATE INDEX IDX_77D6DFD3896DBBDE ON category_prestation (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category_prestation DROP CONSTRAINT FK_77D6DFD3B03A8386');
        $this->addSql('ALTER TABLE category_prestation DROP CONSTRAINT FK_77D6DFD3896DBBDE');
        $this->addSql('DROP INDEX IDX_77D6DFD3B03A8386');
        $this->addSql('DROP INDEX IDX_77D6DFD3896DBBDE');
        $this->addSql('ALTER TABLE category_prestation DROP created_by_id');
        $this->addSql('ALTER TABLE category_prestation DROP updated_by_id');
        $this->addSql('ALTER TABLE category_prestation DROP created_at');
        $this->addSql('ALTER TABLE category_prestation DROP updated_at');
    }
}
