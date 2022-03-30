<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211203133842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_history (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, last_day_date DATETIME NOT NULL, last_day_balance DOUBLE PRECISION NOT NULL, INDEX IDX_EE9164039B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account_history ADD CONSTRAINT FK_EE9164039B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE account DROP last_day_balance, DROP last_day_date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE account_history');
        $this->addSql('ALTER TABLE account ADD last_day_balance DOUBLE PRECISION DEFAULT NULL, ADD last_day_date DATETIME DEFAULT NULL');
    }
}
