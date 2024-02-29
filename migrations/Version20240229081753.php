<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229081753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE calendar');
        $this->addSql('ALTER TABLE event ADD all_day TINYINT(1) NOT NULL, CHANGE background_color background_color VARCHAR(7) NOT NULL, CHANGE border_color border_color VARCHAR(7) NOT NULL, CHANGE text_color text_color VARCHAR(7) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, start DATETIME NOT NULL, end DATETIME NOT NULL, background_color VARCHAR(7) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, border_color VARCHAR(7) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, text_color VARCHAR(7) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE event DROP all_day, CHANGE background_color background_color VARCHAR(7) DEFAULT \'#FFFFFF\' NOT NULL, CHANGE border_color border_color VARCHAR(7) DEFAULT \'#FFFFFF\' NOT NULL, CHANGE text_color text_color VARCHAR(7) DEFAULT \'#000000\' NOT NULL');
    }
}
