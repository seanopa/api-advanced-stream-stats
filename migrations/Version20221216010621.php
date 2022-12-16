<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216010621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_plan (id INT AUTO_INCREMENT NOT NULL, plan_group_id INT NOT NULL, name VARCHAR(100) DEFAULT NULL, frequency VARCHAR(100) DEFAULT NULL, currency VARCHAR(10) DEFAULT NULL, price INT DEFAULT NULL, is_enabled TINYINT(1) DEFAULT 1 NOT NULL, is_default TINYINT(1) DEFAULT 0 NOT NULL, provider VARCHAR(25) DEFAULT NULL, external_id VARCHAR(100) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_D8747EDDF031EE3D (plan_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_plan_feature (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_plan_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_plan_group_feature (id INT AUTO_INCREMENT NOT NULL, group_id INT NOT NULL, feature_id INT NOT NULL, INDEX IDX_F7C7F621FE54D947 (group_id), INDEX IDX_F7C7F62160E4B879 (feature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_plan ADD CONSTRAINT FK_D8747EDDF031EE3D FOREIGN KEY (plan_group_id) REFERENCES app_plan_group (id)');
        $this->addSql('ALTER TABLE app_plan_group_feature ADD CONSTRAINT FK_F7C7F621FE54D947 FOREIGN KEY (group_id) REFERENCES app_plan_group (id)');
        $this->addSql('ALTER TABLE app_plan_group_feature ADD CONSTRAINT FK_F7C7F62160E4B879 FOREIGN KEY (feature_id) REFERENCES app_plan_feature (id)');
        $this->addSql('DROP TABLE app_subscription_plan');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_subscription_plan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, frequency VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, currency VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, price INT DEFAULT NULL, is_enabled TINYINT(1) DEFAULT 1 NOT NULL, is_default TINYINT(1) DEFAULT 0 NOT NULL, provider VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, external_id VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE app_plan DROP FOREIGN KEY FK_D8747EDDF031EE3D');
        $this->addSql('ALTER TABLE app_plan_group_feature DROP FOREIGN KEY FK_F7C7F621FE54D947');
        $this->addSql('ALTER TABLE app_plan_group_feature DROP FOREIGN KEY FK_F7C7F62160E4B879');
        $this->addSql('DROP TABLE app_plan');
        $this->addSql('DROP TABLE app_plan_feature');
        $this->addSql('DROP TABLE app_plan_group');
        $this->addSql('DROP TABLE app_plan_group_feature');
    }
}
