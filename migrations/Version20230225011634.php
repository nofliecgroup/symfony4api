<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225011634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conducteurs (id INT AUTO_INCREMENT NOT NULL, idpersc_id INT DEFAULT NULL, idtrajetc_id INT DEFAULT NULL, INDEX IDX_F4F8B850F22217AC (idpersc_id), INDEX IDX_F4F8B850D8AB945A (idtrajetc_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE idvoiture (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marques (id INT AUTO_INCREMENT NOT NULL, brandnom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnes (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(50) NOT NULL, nom VARCHAR(30) NOT NULL, telephone VARCHAR(10) NOT NULL, ville VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, idpers_id INT DEFAULT NULL, idtrajet_id INT DEFAULT NULL, nseatsreserved INT NOT NULL, INDEX IDX_4DA23995A1632D (idpers_id), INDEX IDX_4DA239730901D6 (idtrajet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trajets (id INT AUTO_INCREMENT NOT NULL, villedepart_id INT DEFAULT NULL, villearrive_id INT DEFAULT NULL, idvoiture_id INT DEFAULT NULL, nbkilometers INT NOT NULL, datetotravel DATETIME DEFAULT NULL, INDEX IDX_FF2B5BA942235125 (villedepart_id), INDEX IDX_FF2B5BA918232926 (villearrive_id), INDEX IDX_FF2B5BA9CC28B580 (idvoiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, idpers_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E995A1632D (idpers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE villes (id INT AUTO_INCREMENT NOT NULL, villenom VARCHAR(50) NOT NULL, codepostal VARCHAR(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voitures (id INT AUTO_INCREMENT NOT NULL, idmarque_id INT DEFAULT NULL, modele VARCHAR(25) NOT NULL, nbseats INT NOT NULL, immatriculation VARCHAR(10) NOT NULL, INDEX IDX_8B58301B363C1047 (idmarque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conducteurs ADD CONSTRAINT FK_F4F8B850F22217AC FOREIGN KEY (idpersc_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE conducteurs ADD CONSTRAINT FK_F4F8B850D8AB945A FOREIGN KEY (idtrajetc_id) REFERENCES trajets (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA23995A1632D FOREIGN KEY (idpers_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA239730901D6 FOREIGN KEY (idtrajet_id) REFERENCES trajets (id)');
        $this->addSql('ALTER TABLE trajets ADD CONSTRAINT FK_FF2B5BA942235125 FOREIGN KEY (villedepart_id) REFERENCES villes (id)');
        $this->addSql('ALTER TABLE trajets ADD CONSTRAINT FK_FF2B5BA918232926 FOREIGN KEY (villearrive_id) REFERENCES villes (id)');
        $this->addSql('ALTER TABLE trajets ADD CONSTRAINT FK_FF2B5BA9CC28B580 FOREIGN KEY (idvoiture_id) REFERENCES voitures (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E995A1632D FOREIGN KEY (idpers_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE voitures ADD CONSTRAINT FK_8B58301B363C1047 FOREIGN KEY (idmarque_id) REFERENCES marques (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conducteurs DROP FOREIGN KEY FK_F4F8B850F22217AC');
        $this->addSql('ALTER TABLE conducteurs DROP FOREIGN KEY FK_F4F8B850D8AB945A');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA23995A1632D');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA239730901D6');
        $this->addSql('ALTER TABLE trajets DROP FOREIGN KEY FK_FF2B5BA942235125');
        $this->addSql('ALTER TABLE trajets DROP FOREIGN KEY FK_FF2B5BA918232926');
        $this->addSql('ALTER TABLE trajets DROP FOREIGN KEY FK_FF2B5BA9CC28B580');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E995A1632D');
        $this->addSql('ALTER TABLE voitures DROP FOREIGN KEY FK_8B58301B363C1047');
        $this->addSql('DROP TABLE conducteurs');
        $this->addSql('DROP TABLE idvoiture');
        $this->addSql('DROP TABLE marques');
        $this->addSql('DROP TABLE personnes');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE trajets');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE villes');
        $this->addSql('DROP TABLE voitures');
    }
}
