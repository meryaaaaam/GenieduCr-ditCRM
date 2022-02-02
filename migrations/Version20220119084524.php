<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119084524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE marchand (id INT AUTO_INCREMENT NOT NULL, concessionnairemarchand_id INT NOT NULL, UNIQUE INDEX UNIQ_9D5311966771C043 (concessionnairemarchand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE marchand ADD CONSTRAINT FK_9D5311966771C043 FOREIGN KEY (concessionnairemarchand_id) REFERENCES concessionnairemarchand (id)');
        $this->addSql('ALTER TABLE status CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD marchand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1D3E6422B1 FOREIGN KEY (marchand_id) REFERENCES marchand (id)');
        $this->addSql('CREATE INDEX IDX_292FFF1D3E6422B1 ON vehicule (marchand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1D3E6422B1');
        $this->addSql('DROP TABLE marchand');
        $this->addSql('ALTER TABLE status CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_292FFF1D3E6422B1 ON vehicule');
        $this->addSql('ALTER TABLE vehicule DROP marchand_id');
    }
}
