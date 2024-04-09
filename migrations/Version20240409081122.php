<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240409081122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_panier');
        $this->addSql('ALTER TABLE package ADD COLUMN language VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_panier (user_id INTEGER NOT NULL, panier_id INTEGER NOT NULL, PRIMARY KEY(user_id, panier_id), CONSTRAINT FK_419E16CFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_419E16CFF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_419E16CFF77D927C ON user_panier (panier_id)');
        $this->addSql('CREATE INDEX IDX_419E16CFA76ED395 ON user_panier (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__package AS SELECT id, name, version, description, documentation, repository, size, install, image FROM package');
        $this->addSql('DROP TABLE package');
        $this->addSql('CREATE TABLE package (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, description CLOB NOT NULL, documentation CLOB DEFAULT NULL, repository CLOB DEFAULT NULL, size INTEGER NOT NULL, install VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO package (id, name, version, description, documentation, repository, size, install, image) SELECT id, name, version, description, documentation, repository, size, install, image FROM __temp__package');
        $this->addSql('DROP TABLE __temp__package');
    }
}
