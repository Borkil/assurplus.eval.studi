<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306160940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE test_entity_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE sinister_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sinister (id INT NOT NULL, adress_of_sinister VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, number_registration VARCHAR(9) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN sinister.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('DROP TABLE test_entity');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE sinister_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE test_entity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE test_entity (id INT NOT NULL, name VARCHAR(50) NOT NULL, age VARCHAR(2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE sinister');
    }
}
