<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103204928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Account, AccountType, AccountMovement, MovementType, Label, Month, Year tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "account" (id SERIAL NOT NULL, type_id INT NOT NULL, owner_id INT NOT NULL, collaborator_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7D3656A4C54C8C93 ON "account" (type_id)');
        $this->addSql('CREATE INDEX IDX_7D3656A47E3C61F9 ON "account" (owner_id)');
        $this->addSql('CREATE INDEX IDX_7D3656A430098C8C ON "account" (collaborator_id)');
        $this->addSql('CREATE TABLE "account_movement" (id SERIAL NOT NULL, account_id INT NOT NULL, label_id INT NOT NULL, movement_type_id INT NOT NULL, year_id INT NOT NULL, month_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8C1377419B6B5FBA ON "account_movement" (account_id)');
        $this->addSql('CREATE INDEX IDX_8C13774133B92F39 ON "account_movement" (label_id)');
        $this->addSql('CREATE INDEX IDX_8C137741EA4ED04A ON "account_movement" (movement_type_id)');
        $this->addSql('CREATE INDEX IDX_8C13774140C1FEA7 ON "account_movement" (year_id)');
        $this->addSql('CREATE INDEX IDX_8C137741A0CBDE4 ON "account_movement" (month_id)');
        $this->addSql('CREATE TABLE "account_type" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DD083989D9B62 ON account_type (slug)');
        $this->addSql('CREATE TABLE "label" (id SERIAL NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA750E8989D9B62 ON label (slug)');
        $this->addSql('CREATE INDEX IDX_EA750E8B03A8386 ON "label" (created_by_id)');
        $this->addSql('CREATE TABLE "month" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, index INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8EB61006989D9B62 ON month (slug)');
        $this->addSql('CREATE TABLE "account_movement_type" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_54F03833989D9B62 ON account_movement_type (slug)');
        $this->addSql('CREATE TABLE "year" (id SERIAL NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "account" ADD CONSTRAINT FK_7D3656A4C54C8C93 FOREIGN KEY (type_id) REFERENCES "account_type" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "account" ADD CONSTRAINT FK_7D3656A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "account" ADD CONSTRAINT FK_7D3656A430098C8C FOREIGN KEY (collaborator_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "account_movement" ADD CONSTRAINT FK_8C1377419B6B5FBA FOREIGN KEY (account_id) REFERENCES "account" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "account_movement" ADD CONSTRAINT FK_8C13774133B92F39 FOREIGN KEY (label_id) REFERENCES "label" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "account_movement" ADD CONSTRAINT FK_8C137741EA4ED04A FOREIGN KEY (movement_type_id) REFERENCES "account_movement_type" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "account_movement" ADD CONSTRAINT FK_8C13774140C1FEA7 FOREIGN KEY (year_id) REFERENCES "year" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "account_movement" ADD CONSTRAINT FK_8C137741A0CBDE4 FOREIGN KEY (month_id) REFERENCES "month" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "label" ADD CONSTRAINT FK_EA750E8B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "account" DROP CONSTRAINT FK_7D3656A4C54C8C93');
        $this->addSql('ALTER TABLE "account" DROP CONSTRAINT FK_7D3656A47E3C61F9');
        $this->addSql('ALTER TABLE "account" DROP CONSTRAINT FK_7D3656A430098C8C');
        $this->addSql('ALTER TABLE "account_movement" DROP CONSTRAINT FK_8C1377419B6B5FBA');
        $this->addSql('ALTER TABLE "account_movement" DROP CONSTRAINT FK_8C13774133B92F39');
        $this->addSql('ALTER TABLE "account_movement" DROP CONSTRAINT FK_8C137741EA4ED04A');
        $this->addSql('ALTER TABLE "account_movement" DROP CONSTRAINT FK_8C13774140C1FEA7');
        $this->addSql('ALTER TABLE "account_movement" DROP CONSTRAINT FK_8C137741A0CBDE4');
        $this->addSql('ALTER TABLE "label" DROP CONSTRAINT FK_EA750E8B03A8386');
        $this->addSql('DROP INDEX UNIQ_4DD083989D9B62');
        $this->addSql('DROP INDEX UNIQ_8EB61006989D9B62');
        $this->addSql('DROP INDEX UNIQ_54F03833989D9B62');
        $this->addSql('DROP INDEX UNIQ_EA750E8989D9B62');
        $this->addSql('DROP TABLE "account"');
        $this->addSql('DROP TABLE "account_movement"');
        $this->addSql('DROP TABLE "account_type"');
        $this->addSql('DROP TABLE "label"');
        $this->addSql('DROP TABLE "month"');
        $this->addSql('DROP TABLE "account_movement_type"');
        $this->addSql('DROP TABLE "year"');
    }
}
