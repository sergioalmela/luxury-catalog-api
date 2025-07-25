<?php

declare(strict_types=1);

namespace DoctrineMigrations\Catalog;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create product table.
 */
final class Version20250724000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create product table with UUID ID, SKU, name, category, and price columns';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product (
            id VARCHAR(36) NOT NULL,
            sku VARCHAR(50) NOT NULL,
            name VARCHAR(255) NOT NULL,
            category VARCHAR(100) NOT NULL,
            price INT NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_1CF73D31F9038C4 ON product (sku)');
        $this->addSql('CREATE INDEX idx_product_category ON product (category)');
        $this->addSql('CREATE INDEX idx_product_price ON product (price)');
        $this->addSql('CREATE INDEX idx_product_category_price ON product (category, price)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product');
    }
}
