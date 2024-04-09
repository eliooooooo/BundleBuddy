<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409081413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__package AS SELECT id, name, version, description, documentation, repository, size, install, image, language FROM package');
        $this->addSql('DROP TABLE package');
        $this->addSql('CREATE TABLE package (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, description CLOB NOT NULL, documentation CLOB DEFAULT NULL, repository CLOB DEFAULT NULL, size INTEGER NOT NULL, install VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO package (id, name, version, description, documentation, repository, size, install, image, language) SELECT id, name, version, description, documentation, repository, size, install, image, language FROM __temp__package');
        $this->addSql('DROP TABLE __temp__package');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__package AS SELECT id, name, version, description, documentation, repository, size, install, image, language FROM package');
        $this->addSql('DROP TABLE package');
        $this->addSql('CREATE TABLE package (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, description CLOB NOT NULL, documentation CLOB DEFAULT NULL, repository CLOB DEFAULT NULL, size INTEGER NOT NULL, install VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, language VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO package (id, name, version, description, documentation, repository, size, install, image, language) SELECT id, name, version, description, documentation, repository, size, install, image, language FROM __temp__package');
        $this->addSql('DROP TABLE __temp__package');
    }
}
