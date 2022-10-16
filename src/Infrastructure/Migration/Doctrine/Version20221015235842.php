<?php

declare(strict_types=1);

namespace ECommerce\Infrastructure\Migration\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221015235842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create customer related tables.';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<POSTGRESQL
            CREATE TABLE customer (
                id UUID PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE
            )
            POSTGRESQL;
        $this->addSql($sql);

        $sql = <<<POSTGRESQL
            CREATE TABLE customer_address (
                id UUID PRIMARY KEY,
                customer_id UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                street1 VARCHAR(255) NOT NULL,
                street2 VARCHAR(255),
                zipcode VARCHAR(5) NOT NULL,
                country_code CHAR(2) NOT NULL,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE
            )
            POSTGRESQL;
        $this->addSql($sql);

        $sql = <<<POSTGRESQL
            CREATE INDEX ON customer_address USING hash(customer_id)
            POSTGRESQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE customer_address');
        $this->addSql('DROP TABLE customer');
    }
}
