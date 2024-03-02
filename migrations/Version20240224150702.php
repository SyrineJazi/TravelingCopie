<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240224150702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY spot');
        $this->addSql('DROP INDEX spot ON avis');
        $this->addSql('ALTER TABLE avis ADD spot_id INT NOT NULL, DROP evaluation, DROP spot, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE Contenu contenu LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF02DF1D37C FOREIGN KEY (spot_id) REFERENCES spot (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF02DF1D37C ON avis (spot_id)');
        $this->addSql('ALTER TABLE spot CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE localisation localisation VARCHAR(255) DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF02DF1D37C');
        $this->addSql('DROP INDEX IDX_8F91ABF02DF1D37C ON avis');
        $this->addSql('ALTER TABLE avis ADD spot INT NOT NULL, CHANGE id id INT NOT NULL, CHANGE contenu Contenu VARCHAR(255) NOT NULL, CHANGE spot_id evaluation INT NOT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT spot FOREIGN KEY (spot) REFERENCES avis (id)');
        $this->addSql('CREATE INDEX spot ON avis (spot)');
        $this->addSql('ALTER TABLE spot MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON spot');
        $this->addSql('ALTER TABLE spot CHANGE id id INT NOT NULL, CHANGE photo photo VARCHAR(255) NOT NULL, CHANGE localisation localisation VARCHAR(255) NOT NULL, CHANGE description description TEXT NOT NULL');
    }
}
