<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216013516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_subscription ADD plan_id INT NOT NULL');
        $this->addSql('ALTER TABLE app_subscription ADD CONSTRAINT FK_61487E521FB354CD FOREIGN KEY (membership_id) REFERENCES app_membership (id)');
        $this->addSql('ALTER TABLE app_subscription ADD CONSTRAINT FK_61487E52E899029B FOREIGN KEY (plan_id) REFERENCES app_plan (id)');
        $this->addSql('CREATE INDEX IDX_61487E52E899029B ON app_subscription (plan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_subscription DROP FOREIGN KEY FK_61487E521FB354CD');
        $this->addSql('ALTER TABLE app_subscription DROP FOREIGN KEY FK_61487E52E899029B');
        $this->addSql('DROP INDEX IDX_61487E52E899029B ON app_subscription');
        $this->addSql('ALTER TABLE app_subscription DROP plan_id');
    }
}
