<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229082000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD all_day TINYINT(1) NOT NULL, CHANGE background_color background_color VARCHAR(7) NOT NULL, CHANGE border_color border_color VARCHAR(7) NOT NULL, CHANGE text_color text_color VARCHAR(7) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP all_day, CHANGE background_color background_color VARCHAR(7) DEFAULT \'#FFFFFF\' NOT NULL, CHANGE border_color border_color VARCHAR(7) DEFAULT \'#FFFFFF\' NOT NULL, CHANGE text_color text_color VARCHAR(7) DEFAULT \'#000000\' NOT NULL');
    }
}
