<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407202028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fill database with all entites';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE `account` (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, owner_id INT NOT NULL, collaborator_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7D3656A4C54C8C93 (type_id), INDEX IDX_7D3656A47E3C61F9 (owner_id), INDEX IDX_7D3656A430098C8C (collaborator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `account_movement` (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, label_id INT DEFAULT NULL, movement_type_id INT NOT NULL, year_id INT NOT NULL, month_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, transaction_direction VARCHAR(255) NOT NULL, is_charged TINYINT(1) NOT NULL, charged_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8C1377419B6B5FBA (account_id), INDEX IDX_8C13774133B92F39 (label_id), INDEX IDX_8C137741EA4ED04A (movement_type_id), INDEX IDX_8C13774140C1FEA7 (year_id), INDEX IDX_8C137741A0CBDE4 (month_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `account_movement_type` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_CBD60480989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `account_type` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4DD083989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `label` (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EA750E8B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `month` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, position INT NOT NULL, UNIQUE INDEX UNIQ_8EB61006989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(180) NOT NULL, lastname VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `year` (id INT AUTO_INCREMENT NOT NULL, value INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account` ADD CONSTRAINT FK_7D3656A4C54C8C93 FOREIGN KEY (type_id) REFERENCES `account_type` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account` ADD CONSTRAINT FK_7D3656A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account` ADD CONSTRAINT FK_7D3656A430098C8C FOREIGN KEY (collaborator_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account_movement` ADD CONSTRAINT FK_8C1377419B6B5FBA FOREIGN KEY (account_id) REFERENCES `account` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account_movement` ADD CONSTRAINT FK_8C13774133B92F39 FOREIGN KEY (label_id) REFERENCES `label` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account_movement` ADD CONSTRAINT FK_8C137741EA4ED04A FOREIGN KEY (movement_type_id) REFERENCES `account_movement_type` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account_movement` ADD CONSTRAINT FK_8C13774140C1FEA7 FOREIGN KEY (year_id) REFERENCES `year` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account_movement` ADD CONSTRAINT FK_8C137741A0CBDE4 FOREIGN KEY (month_id) REFERENCES `month` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `label` ADD CONSTRAINT FK_EA750E8B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE `account` DROP FOREIGN KEY FK_7D3656A4C54C8C93
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account` DROP FOREIGN KEY FK_7D3656A47E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account` DROP FOREIGN KEY FK_7D3656A430098C8C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account_movement` DROP FOREIGN KEY FK_8C1377419B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account_movement` DROP FOREIGN KEY FK_8C13774133B92F39
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account_movement` DROP FOREIGN KEY FK_8C137741EA4ED04A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account_movement` DROP FOREIGN KEY FK_8C13774140C1FEA7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `account_movement` DROP FOREIGN KEY FK_8C137741A0CBDE4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `label` DROP FOREIGN KEY FK_EA750E8B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `account`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `account_movement`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `account_movement_type`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `account_type`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `label`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `month`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `year`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
