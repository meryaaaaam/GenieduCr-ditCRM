<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114134820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `condition` (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehicule ADD conditions_id INT NOT NULL, ADD concessionnaire_id INT NOT NULL, ADD disponiblefinance TINYINT(1) DEFAULT NULL, ADD financement VARCHAR(255) NOT NULL, ADD disponiblegarentie TINYINT(1) DEFAULT NULL, ADD garentie VARCHAR(255) NOT NULL, ADD description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DC5FBDC0F FOREIGN KEY (conditions_id) REFERENCES `condition` (id)');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D8740E698 FOREIGN KEY (concessionnaire_id) REFERENCES concessionnaire (id)');
        $this->addSql('CREATE INDEX IDX_292FFF1DC5FBDC0F ON vehicule (conditions_id)');
        $this->addSql('CREATE INDEX IDX_292FFF1D8740E698 ON vehicule (concessionnaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DC5FBDC0F');
        $this->addSql('DROP TABLE `condition`');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D8740E698');
        $this->addSql('DROP INDEX IDX_292FFF1DC5FBDC0F ON vehicule');
        $this->addSql('DROP INDEX IDX_292FFF1D8740E698 ON vehicule');
        $this->addSql('ALTER TABLE vehicule DROP conditions_id, DROP concessionnaire_id, DROP disponiblefinance, DROP financement, DROP disponiblegarentie, DROP garentie, DROP description');
    }
}
