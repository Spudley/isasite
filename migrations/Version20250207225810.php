<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207225810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create transactions table for sdc isa.';
    }

    public function up(Schema $schema): void
    {
        // Transactions table
        $this->addSql('CREATE TABLE transactions (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            our_transaction_id VARCHAR(255) NOT NULL,
            their_transaction_id VARCHAR(255) NOT NULL,
            fund_name VARCHAR(255) NOT NULL,
            units INTEGER NOT NULL,
            pence_per_unit INTEGER NOT NULL,
            status INTEGER NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE transactions');
    }
}
