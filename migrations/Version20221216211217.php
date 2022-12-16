<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216211217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_transaction (id INT AUTO_INCREMENT NOT NULL, subscription_id INT NOT NULL, amount INT NOT NULL, currency_code VARCHAR(5) NOT NULL, external_id VARCHAR(100) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_2BD2236D9A1887DC (subscription_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_transaction ADD CONSTRAINT FK_2BD2236D9A1887DC FOREIGN KEY (subscription_id) REFERENCES app_subscription (id)');
        $this->addSql('ALTER TABLE app_subscription ADD recurring TINYINT(1) DEFAULT 1 NOT NULL AFTER active, ADD start_date DATETIME DEFAULT NULL, ADD end_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_transaction DROP FOREIGN KEY FK_2BD2236D9A1887DC');
        $this->addSql('DROP TABLE app_transaction');
        $this->addSql('ALTER TABLE app_subscription DROP recurring, DROP start_date, DROP end_date');
    }
}
