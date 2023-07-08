<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230708160055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estimate ADD car_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE estimate ADD car_brand VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE estimate ADD car_model VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE estimate ADD car_type VARCHAR(15) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE estimate DROP car_id');
        $this->addSql('ALTER TABLE estimate DROP car_brand');
        $this->addSql('ALTER TABLE estimate DROP car_model');
        $this->addSql('ALTER TABLE estimate DROP car_type');
    }
}
