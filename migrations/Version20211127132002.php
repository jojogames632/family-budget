<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211127132002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, number INT NOT NULL, update_date DATE NOT NULL, balance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE budget (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, movement VARCHAR(255) NOT NULL, planned_amount DOUBLE PRECISION NOT NULL, deadline_number DOUBLE PRECISION NOT NULL, deadline_word VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_73F2F77B12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, unactive_date DATE DEFAULT NULL, type VARCHAR(255) NOT NULL, color VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, bank_date DATE NOT NULL, name VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, balance DOUBLE PRECISION NOT NULL, INDEX IDX_723705D19B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_splitting (id INT AUTO_INCREMENT NOT NULL, transaction_id INT NOT NULL, category_id INT NOT NULL, recurring_category_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_241CAAD72FC0CB0F (transaction_id), INDEX IDX_241CAAD712469DE2 (category_id), INDEX IDX_241CAAD7B3210295 (recurring_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE budget ADD CONSTRAINT FK_73F2F77B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE transaction_splitting ADD CONSTRAINT FK_241CAAD72FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE transaction_splitting ADD CONSTRAINT FK_241CAAD712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE transaction_splitting ADD CONSTRAINT FK_241CAAD7B3210295 FOREIGN KEY (recurring_category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D19B6B5FBA');
        $this->addSql('ALTER TABLE budget DROP FOREIGN KEY FK_73F2F77B12469DE2');
        $this->addSql('ALTER TABLE transaction_splitting DROP FOREIGN KEY FK_241CAAD712469DE2');
        $this->addSql('ALTER TABLE transaction_splitting DROP FOREIGN KEY FK_241CAAD7B3210295');
        $this->addSql('ALTER TABLE transaction_splitting DROP FOREIGN KEY FK_241CAAD72FC0CB0F');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE budget');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_splitting');
    }
}
