<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114125506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status ADD detail TINYINT(1) DEFAULT NULL, ADD wholesale TINYINT(1) DEFAULT NULL, ADD enenchere TINYINT(1) DEFAULT NULL, ADD vendu TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicule DROP volantajustable, DROP carproof');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status DROP detail, DROP wholesale, DROP enenchere, DROP vendu');
        $this->addSql('ALTER TABLE vehicule ADD volantajustable TINYINT(1) DEFAULT NULL, ADD carproof VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
