<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429013833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoriearticle (idcat INT AUTO_INCREMENT NOT NULL, nomcat VARCHAR(255) NOT NULL, PRIMARY KEY(idcat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD idcat INT DEFAULT NULL, ADD rate DOUBLE PRECISION DEFAULT NULL, CHANGE categorie categorie VARCHAR(30) DEFAULT NULL, CHANGE image_article image_article TEXT DEFAULT NULL, CHANGE prix prix INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66875B18CF FOREIGN KEY (idcat) REFERENCES categoriearticle (idcat)');
        $this->addSql('CREATE INDEX idcat ON article (idcat)');
        $this->addSql('CREATE INDEX id_article ON article (id_article)');
        $this->addSql('ALTER TABLE categorie CHANGE id_categorie id_categorie INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY fk_event');
        $this->addSql('DROP INDEX fk_event ON event');
        $this->addSql('ALTER TABLE event CHANGE categories_id categories_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement RENAME INDEX fk_b1dc7a1e349ba55 TO fk_paiement');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66875B18CF');
        $this->addSql('DROP TABLE categoriearticle');
        $this->addSql('DROP INDEX idcat ON article');
        $this->addSql('DROP INDEX id_article ON article');
        $this->addSql('ALTER TABLE article DROP idcat, DROP rate, CHANGE categorie categorie VARCHAR(30) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE image_article image_article TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE prix prix INT NOT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE id_categorie id_categorie INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE categories_id categories_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT fk_event FOREIGN KEY (categories_id) REFERENCES categorie (id_categorie)');
        $this->addSql('CREATE INDEX fk_event ON event (categories_id)');
        $this->addSql('ALTER TABLE paiement RENAME INDEX fk_paiement TO FK_B1DC7A1E349BA55');
    }
}
