<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210428005326 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherant ADD reset_token VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE `admin` ADD reset_token VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD reset_token VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY fk_paiement');
        $this->addSql('ALTER TABLE paiement CHANGE num_piece num_piece INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E349BA55 FOREIGN KEY (num_piece) REFERENCES entete_facture (num_piece)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherant DROP reset_token');
        $this->addSql('ALTER TABLE `admin` DROP reset_token');
        $this->addSql('ALTER TABLE client DROP reset_token');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E349BA55');
        $this->addSql('ALTER TABLE paiement CHANGE num_piece num_piece INT NOT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT fk_paiement FOREIGN KEY (num_piece) REFERENCES entete_facture (num_piece) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
