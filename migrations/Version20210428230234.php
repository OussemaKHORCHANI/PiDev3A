<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210428230234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherant (idA INT AUTO_INCREMENT NOT NULL, nom VARCHAR(15) NOT NULL, prenom VARCHAR(15) NOT NULL, cin INT NOT NULL, address VARCHAR(20) NOT NULL, nomTerain VARCHAR(20) NOT NULL, email VARCHAR(30) NOT NULL, numTel INT NOT NULL, mdp VARCHAR(30) NOT NULL, reset_token VARCHAR(50) DEFAULT NULL, PRIMARY KEY(idA)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(15) NOT NULL, prenom VARCHAR(15) NOT NULL, username VARCHAR(15) NOT NULL, email VARCHAR(30) NOT NULL, mdp VARCHAR(30) NOT NULL, reset_token VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id_article INT AUTO_INCREMENT NOT NULL, idcat INT DEFAULT NULL, libelle VARCHAR(30) NOT NULL, categorie VARCHAR(30) DEFAULT NULL, image_article TEXT DEFAULT NULL, prix INT DEFAULT NULL, qt_article INT NOT NULL, ref INT NOT NULL, rate DOUBLE PRECISION DEFAULT NULL, INDEX idcat (idcat), INDEX id_article (id_article), PRIMARY KEY(id_article)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id_categorie INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(100) NOT NULL, PRIMARY KEY(id_categorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoriearticle (idcat INT AUTO_INCREMENT NOT NULL, nomcat VARCHAR(255) NOT NULL, PRIMARY KEY(idcat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (idC INT AUTO_INCREMENT NOT NULL, nom VARCHAR(15) NOT NULL, prenom VARCHAR(15) NOT NULL, address VARCHAR(20) NOT NULL, numTelC INT NOT NULL, email VARCHAR(30) NOT NULL, mdp VARCHAR(30) NOT NULL, reset_token VARCHAR(50) DEFAULT NULL, PRIMARY KEY(idC)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id_club INT AUTO_INCREMENT NOT NULL, nom_club VARCHAR(30) NOT NULL, nbr_joueurs INT NOT NULL, PRIMARY KEY(id_club)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clubcomp (idclubcomp INT AUTO_INCREMENT NOT NULL, id_club INT DEFAULT NULL, id_competition INT DEFAULT NULL, nom_club VARCHAR(20) NOT NULL, INDEX fk_club (id_club), INDEX fk_comp (id_competition), PRIMARY KEY(idclubcomp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clubx (id_clubx INT AUTO_INCREMENT NOT NULL, id_club INT DEFAULT NULL, id INT DEFAULT NULL, Nom_club VARCHAR(30) NOT NULL, Nom_joueur VARCHAR(30) NOT NULL, INDEX clubet (id_club), INDEX joueuret (id), PRIMARY KEY(id_clubx)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competition (id_competition INT AUTO_INCREMENT NOT NULL, nom_competition VARCHAR(20) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, PRIMARY KEY(id_competition)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, date VARCHAR(200) NOT NULL, nomterrain VARCHAR(200) NOT NULL, nomequipe VARCHAR(200) NOT NULL, email VARCHAR(200) NOT NULL, etat VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detail_facture (id_article INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(30) NOT NULL, num_piece INT NOT NULL, qt INT NOT NULL, type VARCHAR(5) NOT NULL, ref_article INT NOT NULL, ref_facture INT NOT NULL, INDEX num_piece (num_piece), PRIMARY KEY(id_article)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entete_facture (num_piece INT AUTO_INCREMENT NOT NULL, type VARCHAR(5) NOT NULL, date_exp DATE NOT NULL, tier INT NOT NULL, ref_facture INT NOT NULL, PRIMARY KEY(num_piece)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (idequipe INT AUTO_INCREMENT NOT NULL, nom VARCHAR(20) NOT NULL, nombre INT NOT NULL, PRIMARY KEY(idequipe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, categories_id VARCHAR(255) DEFAULT NULL, Nom VARCHAR(255) NOT NULL, Description VARCHAR(255) DEFAULT NULL, Lieu_event VARCHAR(255) DEFAULT NULL, Date_event DATE DEFAULT NULL, Prix DOUBLE PRECISION DEFAULT NULL, etat INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (idFacture INT AUTO_INCREMENT NOT NULL, sommeFacture DOUBLE PRECISION NOT NULL, datePaiement DATE NOT NULL, idRes INT DEFAULT NULL, INDEX fk_facture1 (idRes), PRIMARY KEY(idFacture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fidelite (idfidelite INT AUTO_INCREMENT NOT NULL, idClient INT DEFAULT NULL, INDEX fk_Client (idClient), PRIMARY KEY(idfidelite)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueurs (id INT AUTO_INCREMENT NOT NULL, id_club INT DEFAULT NULL, nom VARCHAR(20) NOT NULL, prenom VARCHAR(20) NOT NULL, age INT NOT NULL, nom_club VARCHAR(11) DEFAULT NULL, email VARCHAR(30) NOT NULL, INDEX fk_clubs (id_club), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (num_piece INT DEFAULT NULL, idP INT AUTO_INCREMENT NOT NULL, Ref VARCHAR(50) NOT NULL, num_ced INT NOT NULL, num_cb INT NOT NULL, type VARCHAR(50) NOT NULL, INDEX fk_paiement (num_piece), PRIMARY KEY(idP)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id_promo INT AUTO_INCREMENT NOT NULL, pourcentage INT DEFAULT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, PRIMARY KEY(id_promo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (idclient INT DEFAULT NULL, idRes INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, heureDebut TIME NOT NULL, heureFin TIME NOT NULL, idTerrain INT DEFAULT NULL, INDEX fk_reservation (idTerrain), INDEX fkClient (idclient), PRIMARY KEY(idRes)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terrain (idTerrain INT AUTO_INCREMENT NOT NULL, nomTerrain VARCHAR(30) NOT NULL, adresse VARCHAR(30) NOT NULL, etat VARCHAR(50) NOT NULL, description VARCHAR(500) DEFAULT NULL, photo TEXT DEFAULT NULL, PRIMARY KEY(idTerrain)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66875B18CF FOREIGN KEY (idcat) REFERENCES categoriearticle (idcat)');
        $this->addSql('ALTER TABLE clubcomp ADD CONSTRAINT FK_250356D933CE2470 FOREIGN KEY (id_club) REFERENCES club (id_club)');
        $this->addSql('ALTER TABLE clubcomp ADD CONSTRAINT FK_250356D9AD18E146 FOREIGN KEY (id_competition) REFERENCES competition (id_competition)');
        $this->addSql('ALTER TABLE clubx ADD CONSTRAINT FK_326FE8AB33CE2470 FOREIGN KEY (id_club) REFERENCES club (id_club)');
        $this->addSql('ALTER TABLE clubx ADD CONSTRAINT FK_326FE8ABBF396750 FOREIGN KEY (id) REFERENCES joueurs (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641058FAC7CF FOREIGN KEY (idRes) REFERENCES reservation (idRes)');
        $this->addSql('ALTER TABLE fidelite ADD CONSTRAINT FK_EF425B23A455ACCF FOREIGN KEY (idClient) REFERENCES client (idC)');
        $this->addSql('ALTER TABLE joueurs ADD CONSTRAINT FK_F0FD889D33CE2470 FOREIGN KEY (id_club) REFERENCES club (id_club)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E349BA55 FOREIGN KEY (num_piece) REFERENCES entete_facture (num_piece)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A3F9A9F9 FOREIGN KEY (idclient) REFERENCES client (idC)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955D8CF3843 FOREIGN KEY (idTerrain) REFERENCES terrain (idTerrain)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66875B18CF');
        $this->addSql('ALTER TABLE fidelite DROP FOREIGN KEY FK_EF425B23A455ACCF');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A3F9A9F9');
        $this->addSql('ALTER TABLE clubcomp DROP FOREIGN KEY FK_250356D933CE2470');
        $this->addSql('ALTER TABLE clubx DROP FOREIGN KEY FK_326FE8AB33CE2470');
        $this->addSql('ALTER TABLE joueurs DROP FOREIGN KEY FK_F0FD889D33CE2470');
        $this->addSql('ALTER TABLE clubcomp DROP FOREIGN KEY FK_250356D9AD18E146');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E349BA55');
        $this->addSql('ALTER TABLE clubx DROP FOREIGN KEY FK_326FE8ABBF396750');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641058FAC7CF');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955D8CF3843');
        $this->addSql('DROP TABLE adherant');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categoriearticle');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE clubcomp');
        $this->addSql('DROP TABLE clubx');
        $this->addSql('DROP TABLE competition');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE detail_facture');
        $this->addSql('DROP TABLE entete_facture');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE fidelite');
        $this->addSql('DROP TABLE joueurs');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE terrain');
    }
}
