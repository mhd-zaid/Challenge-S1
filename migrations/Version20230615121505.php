<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615121505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ALTER lastname DROP NOT NULL');
        $this->addSql('ALTER TABLE customer ALTER firstname DROP NOT NULL');
        $this->addSql('ALTER TABLE customer ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE customer ALTER password DROP NOT NULL');
        $this->addSql('ALTER TABLE customer ALTER address DROP NOT NULL');
        $this->addSql('ALTER TABLE customer ALTER phone DROP NOT NULL');
        $this->addSql('ALTER TABLE customer ALTER validation_token DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "customer" ALTER lastname SET NOT NULL');
        $this->addSql('ALTER TABLE "customer" ALTER firstname SET NOT NULL');
        $this->addSql('ALTER TABLE "customer" ALTER email SET NOT NULL');
        $this->addSql('ALTER TABLE "customer" ALTER password SET NOT NULL');
        $this->addSql('ALTER TABLE "customer" ALTER address SET NOT NULL');
        $this->addSql('ALTER TABLE "customer" ALTER phone SET NOT NULL');
        $this->addSql('ALTER TABLE "customer" ALTER validation_token SET NOT NULL');
    }
}
