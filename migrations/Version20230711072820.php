<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230711072820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD is_active BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE prestation ALTER workforce TYPE NUMERIC(5, 2)');
        $this->addSql('ALTER TABLE prestation ALTER deleted_at SET NOT NULL');
        $this->addSql('ALTER TABLE product ALTER title TYPE VARCHAR(180)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD2B36786B ON product (title)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE prestation ALTER workforce TYPE INT');
        $this->addSql('ALTER TABLE prestation ALTER deleted_at DROP NOT NULL');
        $this->addSql('DROP INDEX UNIQ_D34A04AD2B36786B');
        $this->addSql('ALTER TABLE product ALTER title TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE category DROP is_active');
    }
}
