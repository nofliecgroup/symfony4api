<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228074757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE idvoiture');
        $this->addSql('ALTER TABLE conducteurs CHANGE idtrajetc_id idtrajetc_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservations DROP idvoiture');
        $this->addSql('ALTER TABLE trajets DROP FOREIGN KEY FK_FF2B5BA942235125');
        $this->addSql('DROP INDEX IDX_FF2B5BA942235125 ON trajets');
        $this->addSql('ALTER TABLE trajets CHANGE villedepart_id ville_depart INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trajets ADD CONSTRAINT FK_FF2B5BA9DDDF1A2 FOREIGN KEY (ville_depart) REFERENCES villes (id)');
        $this->addSql('CREATE INDEX IDX_FF2B5BA9DDDF1A2 ON trajets (ville_depart)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE idvoiture (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE conducteurs CHANGE idtrajetc_id idtrajetc_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trajets DROP FOREIGN KEY FK_FF2B5BA9DDDF1A2');
        $this->addSql('DROP INDEX IDX_FF2B5BA9DDDF1A2 ON trajets');
        $this->addSql('ALTER TABLE trajets CHANGE ville_depart villedepart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trajets ADD CONSTRAINT FK_FF2B5BA942235125 FOREIGN KEY (villedepart_id) REFERENCES villes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FF2B5BA942235125 ON trajets (villedepart_id)');
        $this->addSql('ALTER TABLE reservations ADD idvoiture INT NOT NULL');
    }
}
