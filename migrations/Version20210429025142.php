<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429025142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id_article INT AUTO_INCREMENT NOT NULL, idcat INT DEFAULT NULL, libelle VARCHAR(30) NOT NULL, categorie VARCHAR(30) DEFAULT NULL, image_article TEXT DEFAULT NULL, prix INT DEFAULT NULL, qt_article INT NOT NULL, ref INT NOT NULL, rate DOUBLE PRECISION DEFAULT NULL, INDEX idcat (idcat), INDEX id_article (id_article), PRIMARY KEY(id_article)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoriearticle (idcat INT AUTO_INCREMENT NOT NULL, nomcat VARCHAR(255) NOT NULL, PRIMARY KEY(idcat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66875B18CF FOREIGN KEY (idcat) REFERENCES categoriearticle (idcat)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66875B18CF');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE categoriearticle');
    }
}
