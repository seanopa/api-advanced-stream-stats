<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215203732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_api_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT NULL, expires_at DATETIME DEFAULT NULL, INDEX IDX_D3CDAADEA76ED395 (user_id), UNIQUE INDEX UNIQUE_TOKEN (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_subscription_membership (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, subscription_plan_id INT NOT NULL, external_id VARCHAR(100) NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, expires_at DATETIME DEFAULT NULL, INDEX IDX_54EDF209A76ED395 (user_id), INDEX IDX_54EDF2099B8CE200 (subscription_plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_subscription_plan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, frequency VARCHAR(100) DEFAULT NULL, currency VARCHAR(10) DEFAULT NULL, price INT DEFAULT NULL, is_enabled TINYINT(1) DEFAULT 1 NOT NULL, is_default TINYINT(1) DEFAULT 0 NOT NULL, provider VARCHAR(25) DEFAULT NULL, external_id VARCHAR(100) DEFAULT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(25) DEFAULT NULL, last_name VARCHAR(25) DEFAULT NULL, username VARCHAR(25) DEFAULT NULL, is_deleted TINYINT(1) DEFAULT 0 NOT NULL, is_email_verified TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQUE_EMAIL (email), UNIQUE INDEX UNIQUE_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_api_token ADD CONSTRAINT FK_D3CDAADEA76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_subscription_membership ADD CONSTRAINT FK_54EDF209A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_subscription_membership ADD CONSTRAINT FK_54EDF2099B8CE200 FOREIGN KEY (subscription_plan_id) REFERENCES app_subscription_plan (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_api_token DROP FOREIGN KEY FK_D3CDAADEA76ED395');
        $this->addSql('ALTER TABLE app_subscription_membership DROP FOREIGN KEY FK_54EDF209A76ED395');
        $this->addSql('ALTER TABLE app_subscription_membership DROP FOREIGN KEY FK_54EDF2099B8CE200');
        $this->addSql('DROP TABLE app_api_token');
        $this->addSql('DROP TABLE app_subscription_membership');
        $this->addSql('DROP TABLE app_subscription_plan');
        $this->addSql('DROP TABLE app_user');
    }
}
