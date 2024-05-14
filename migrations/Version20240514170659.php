<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514170659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lista_seguimiento (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nombre VARCHAR(25) NOT NULL, descripcion VARCHAR(100) DEFAULT NULL, INDEX IDX_D875AE67A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lista_seguimiento_pelicula (lista_seguimiento_id INT NOT NULL, pelicula_id INT NOT NULL, INDEX IDX_9A844DE83B320C99 (lista_seguimiento_id), INDEX IDX_9A844DE870713909 (pelicula_id), PRIMARY KEY(lista_seguimiento_id, pelicula_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pelicula (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(50) NOT NULL, director VARCHAR(100) NOT NULL, categoria VARCHAR(20) NOT NULL, duracion INT NOT NULL, sinopsis VARCHAR(255) DEFAULT NULL, imagen VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reproduccion (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, pelicula_id INT DEFAULT NULL, fecha DATE NOT NULL, INDEX IDX_6F8E55CEA76ED395 (user_id), INDEX IDX_6F8E55CE70713909 (pelicula_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suscripcion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, duracion INT NOT NULL, precio DOUBLE PRECISION NOT NULL, descripcion VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, suscripcion_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(50) NOT NULL, apellidos VARCHAR(75) NOT NULL, ciudad VARCHAR(50) NOT NULL, cp INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649189E045D (suscripcion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lista_seguimiento ADD CONSTRAINT FK_D875AE67A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lista_seguimiento_pelicula ADD CONSTRAINT FK_9A844DE83B320C99 FOREIGN KEY (lista_seguimiento_id) REFERENCES lista_seguimiento (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lista_seguimiento_pelicula ADD CONSTRAINT FK_9A844DE870713909 FOREIGN KEY (pelicula_id) REFERENCES pelicula (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reproduccion ADD CONSTRAINT FK_6F8E55CEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reproduccion ADD CONSTRAINT FK_6F8E55CE70713909 FOREIGN KEY (pelicula_id) REFERENCES pelicula (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649189E045D FOREIGN KEY (suscripcion_id) REFERENCES suscripcion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lista_seguimiento DROP FOREIGN KEY FK_D875AE67A76ED395');
        $this->addSql('ALTER TABLE lista_seguimiento_pelicula DROP FOREIGN KEY FK_9A844DE83B320C99');
        $this->addSql('ALTER TABLE lista_seguimiento_pelicula DROP FOREIGN KEY FK_9A844DE870713909');
        $this->addSql('ALTER TABLE reproduccion DROP FOREIGN KEY FK_6F8E55CEA76ED395');
        $this->addSql('ALTER TABLE reproduccion DROP FOREIGN KEY FK_6F8E55CE70713909');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649189E045D');
        $this->addSql('DROP TABLE lista_seguimiento');
        $this->addSql('DROP TABLE lista_seguimiento_pelicula');
        $this->addSql('DROP TABLE pelicula');
        $this->addSql('DROP TABLE reproduccion');
        $this->addSql('DROP TABLE suscripcion');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
