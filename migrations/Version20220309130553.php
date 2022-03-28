<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309130553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programme (id INT AUTO_INCREMENT NOT NULL, trainer_id INT DEFAULT NULL, room_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, is_online TINYINT(1) NOT NULL, INDEX IDX_3DDCB9FFFB08EDF6 (trainer_id), INDEX IDX_3DDCB9FF54177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programmes_customers (programme_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4421C58C62BB7AEE (programme_id), INDEX IDX_4421C58CA76ED395 (user_id), PRIMARY KEY(programme_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, capacity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, cnp VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FFFB08EDF6 FOREIGN KEY (trainer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE programme ADD CONSTRAINT FK_3DDCB9FF54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE programmes_customers ADD CONSTRAINT FK_4421C58C62BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE programmes_customers ADD CONSTRAINT FK_4421C58CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE programmes_customers DROP FOREIGN KEY FK_4421C58C62BB7AEE');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FF54177093');
        $this->addSql('ALTER TABLE programme DROP FOREIGN KEY FK_3DDCB9FFFB08EDF6');
        $this->addSql('ALTER TABLE programmes_customers DROP FOREIGN KEY FK_4421C58CA76ED395');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE programme');
        $this->addSql('DROP TABLE programmes_customers');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE user');
    }
}