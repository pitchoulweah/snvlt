<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230204214657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE deif.utilisateur_id_utilisateur_seq CASCADE');
        $this->addSql('CREATE SEQUENCE utilisateur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE utilisateur (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE deif.utilisateur');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SCHEMA deif');
        $this->addSql('DROP SEQUENCE utilisateur_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE deif.utilisateur_id_utilisateur_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE deif.utilisateur (id_utilisateur SERIAL NOT NULL, nom_utilisateur VARCHAR(100) NOT NULL, prenoms_utilisateur VARCHAR(255) NOT NULL, email_utilisateur VARCHAR(100) NOT NULL, mdp_utilisateur VARCHAR(100) NOT NULL, code_groupe INT NOT NULL, annee INT DEFAULT NULL, photo BYTEA DEFAULT NULL, statut BOOLEAN DEFAULT NULL, iddle BOOLEAN DEFAULT NULL, codeexploitant INT DEFAULT NULL, role_user INT DEFAULT NULL, code_service INT DEFAULT NULL, code_direction INT DEFAULT NULL, agent_sodef BOOLEAN DEFAULT NULL, codeindustriel INT DEFAULT NULL, actif BOOLEAN DEFAULT NULL, nouveau BOOLEAN DEFAULT NULL, mobile VARCHAR(100) DEFAULT NULL, profile_user VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id_utilisateur))');
        $this->addSql('DROP TABLE utilisateur');
    }
}
