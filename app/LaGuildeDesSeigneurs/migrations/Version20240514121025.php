<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514121025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` ADD gls_name VARCHAR(20) NOT NULL, ADD gls_caste VARCHAR(20) DEFAULT NULL, ADD gls_knowledge VARCHAR(20) DEFAULT NULL, ADD gls_intelligence SMALLINT DEFAULT NULL, ADD gls_strength SMALLINT DEFAULT NULL, ADD gls_slug VARCHAR(20) NOT NULL, ADD gls_kind VARCHAR(20) NOT NULL, ADD gls_creation DATETIME DEFAULT NULL, ADD gls_modification DATETIME DEFAULT NULL, DROP name, DROP caste, DROP knowledge, DROP intelligence, DROP strength, DROP slug, DROP kind, DROP creation, DROP modification, CHANGE identifier gls_identifier VARCHAR(40) NOT NULL, CHANGE surname gls_surname VARCHAR(50) NOT NULL, CHANGE image gls_image VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` ADD name VARCHAR(20) NOT NULL, ADD caste VARCHAR(20) DEFAULT NULL, ADD knowledge VARCHAR(20) DEFAULT NULL, ADD intelligence SMALLINT DEFAULT NULL, ADD strength SMALLINT DEFAULT NULL, ADD slug VARCHAR(20) NOT NULL, ADD kind VARCHAR(20) NOT NULL, ADD creation DATETIME DEFAULT NULL, ADD modification DATETIME DEFAULT NULL, DROP gls_name, DROP gls_caste, DROP gls_knowledge, DROP gls_intelligence, DROP gls_strength, DROP gls_slug, DROP gls_kind, DROP gls_creation, DROP gls_modification, CHANGE gls_identifier identifier VARCHAR(40) NOT NULL, CHANGE gls_surname surname VARCHAR(50) NOT NULL, CHANGE gls_image image VARCHAR(50) DEFAULT NULL');
    }
}
