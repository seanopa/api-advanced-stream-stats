<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216002717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_membership (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, external_id VARCHAR(100) NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, expires_at DATETIME DEFAULT NULL, INDEX IDX_D0E479F9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_subscription (id INT AUTO_INCREMENT NOT NULL, external_id VARCHAR(100) DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, membership_id INT NOT NULL, INDEX IDX_61487E521FB354CD (membership_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_membership ADD CONSTRAINT FK_D0E479F9A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_subscription_membership DROP FOREIGN KEY FK_54EDF2099B8CE200');
        $this->addSql('ALTER TABLE app_subscription_membership DROP FOREIGN KEY FK_54EDF209A76ED395');
        $this->addSql('DROP TABLE app_subscription_membership');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_subscription_membership (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, subscription_plan_id INT NOT NULL, external_id VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_active TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, expires_at DATETIME DEFAULT NULL, INDEX IDX_54EDF209A76ED395 (user_id), INDEX IDX_54EDF2099B8CE200 (subscription_plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE app_subscription_membership ADD CONSTRAINT FK_54EDF2099B8CE200 FOREIGN KEY (subscription_plan_id) REFERENCES app_subscription_plan (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE app_subscription_membership ADD CONSTRAINT FK_54EDF209A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE app_membership DROP FOREIGN KEY FK_D0E479F9A76ED395');
        $this->addSql('DROP TABLE app_membership');
        $this->addSql('DROP TABLE app_subscription');
    }
}
