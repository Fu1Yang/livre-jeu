<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240603205635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alternative (id INT AUTO_INCREMENT NOT NULL, etape_suivante_id INT NOT NULL, texte_ambiance VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_EFF5DFA62A0957E (etape_suivante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, nom_fichier VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aventure (id INT AUTO_INCREMENT NOT NULL, premiere_etape_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1E56DE4B9551B165 (premiere_etape_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etape (id INT AUTO_INCREMENT NOT NULL, aventure_id INT DEFAULT NULL, fin_aventure_id INT DEFAULT NULL, texte_ambiance VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_285F75DD873DBB5F (aventure_id), INDEX IDX_285F75DDC3DCFBBF (fin_aventure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partie (id INT AUTO_INCREMENT NOT NULL, aventurier_id INT NOT NULL, aventure_id INT NOT NULL, etape_id INT NOT NULL, date_partie DATETIME NOT NULL, INDEX IDX_59B1F3DEDDC7141 (aventurier_id), INDEX IDX_59B1F3D873DBB5F (aventure_id), INDEX IDX_59B1F3D4A8CA2AD (etape_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnage (id INT AUTO_INCREMENT NOT NULL, avatar_id INT NOT NULL, prenom VARCHAR(255) NOT NULL, INDEX IDX_6AEA486D86383B10 (avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alternative ADD CONSTRAINT FK_EFF5DFA62A0957E FOREIGN KEY (etape_suivante_id) REFERENCES etape (id)');
        $this->addSql('ALTER TABLE aventure ADD CONSTRAINT FK_1E56DE4B9551B165 FOREIGN KEY (premiere_etape_id) REFERENCES etape (id)');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DD873DBB5F FOREIGN KEY (aventure_id) REFERENCES aventure (id)');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DDC3DCFBBF FOREIGN KEY (fin_aventure_id) REFERENCES aventure (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3DEDDC7141 FOREIGN KEY (aventurier_id) REFERENCES personnage (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D873DBB5F FOREIGN KEY (aventure_id) REFERENCES aventure (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D4A8CA2AD FOREIGN KEY (etape_id) REFERENCES etape (id)');
        $this->addSql('ALTER TABLE personnage ADD CONSTRAINT FK_6AEA486D86383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alternative DROP FOREIGN KEY FK_EFF5DFA62A0957E');
        $this->addSql('ALTER TABLE aventure DROP FOREIGN KEY FK_1E56DE4B9551B165');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DD873DBB5F');
        $this->addSql('ALTER TABLE etape DROP FOREIGN KEY FK_285F75DDC3DCFBBF');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3DEDDC7141');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D873DBB5F');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D4A8CA2AD');
        $this->addSql('ALTER TABLE personnage DROP FOREIGN KEY FK_6AEA486D86383B10');
        $this->addSql('DROP TABLE alternative');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE aventure');
        $this->addSql('DROP TABLE etape');
        $this->addSql('DROP TABLE partie');
        $this->addSql('DROP TABLE personnage');
    }
}
