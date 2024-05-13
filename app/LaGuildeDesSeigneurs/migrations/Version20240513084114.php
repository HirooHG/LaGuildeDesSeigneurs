<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240513084114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `building` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, caste VARCHAR(20) NOT NULL, strength INT NOT NULL, image VARCHAR(50) DEFAULT NULL, slug VARCHAR(20) NOT NULL, rate INT NOT NULL, identifier VARCHAR(50) NOT NULL, creation DATETIME NOT NULL, modification DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, identifier VARCHAR(40) NOT NULL, name VARCHAR(20) NOT NULL, surname VARCHAR(50) NOT NULL, caste VARCHAR(20) DEFAULT NULL, knowledge VARCHAR(20) DEFAULT NULL, intelligence SMALLINT DEFAULT NULL, strength SMALLINT DEFAULT NULL, image VARCHAR(50) DEFAULT NULL, slug VARCHAR(20) NOT NULL, kind VARCHAR(20) NOT NULL, creation DATETIME DEFAULT NULL, modification DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `building`');
        $this->addSql('DROP TABLE `character`');
    }
}
