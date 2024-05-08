<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505205446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier_product (panier_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_29F0C02CF77D927C (panier_id), INDEX IDX_29F0C02C4584665A (product_id), PRIMARY KEY(panier_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_rating (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, nbrratting INT NOT NULL, INDEX IDX_BAF567864584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier_product ADD CONSTRAINT FK_29F0C02CF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_product ADD CONSTRAINT FK_29F0C02C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_rating ADD CONSTRAINT FK_BAF567864584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_COMMAND');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE rating');
        $this->addSql('ALTER TABLE categorie CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX personneId ON panier');
        $this->addSql('DROP INDEX fk_productId ON panier');
        $this->addSql('ALTER TABLE panier DROP productId, DROP personneId, CHANGE prix_total prix_total DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD id_categorie_id INT DEFAULT NULL, CHANGE prod_name prod_name VARCHAR(255) NOT NULL, CHANGE type type VARCHAR(255) NOT NULL, CHANGE stock stock VARCHAR(255) NOT NULL, CHANGE price price VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD9F34925F ON product (id_categorie_id)');
        $this->addSql('DROP INDEX fk_personneIdx ON reservation');
        $this->addSql('ALTER TABLE reservation ADD tableid_id INT DEFAULT NULL, ADD number_personnes INT NOT NULL, DROP personneId, DROP numberPersonnes, CHANGE status status VARCHAR(255) NOT NULL, CHANGE customerName customer_name VARCHAR(255) NOT NULL, CHANGE dateTime date_time DATE NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495568B7AE87 FOREIGN KEY (tableid_id) REFERENCES restaurant_table (id)');
        $this->addSql('CREATE INDEX IDX_42C8495568B7AE87 ON reservation (tableid_id)');
        $this->addSql('ALTER TABLE restaurant_table DROP FOREIGN KEY fk_reservationId');
        $this->addSql('DROP INDEX fk_reservationId ON restaurant_table');
        $this->addSql('ALTER TABLE restaurant_table DROP reservationId, CHANGE available available VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD image VARCHAR(255) DEFAULT NULL, ADD is_verified TINYINT(1) NOT NULL, ADD is_active TINYINT(1) NOT NULL, DROP role, CHANGE name name VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE num_tel num_tel INT DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_8D93D6495E237E06A625945BE7927C74 ON user (name, prenom, email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, idPanier INT DEFAULT NULL, date DATE DEFAULT NULL, adresse_dest VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, prix_total DOUBLE PRECISION DEFAULT NULL, status VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, idPersonne INT DEFAULT NULL, averageRating DOUBLE PRECISION DEFAULT NULL, INDEX FK_commande (idPanier), INDEX FK_commaddnde (idPersonne), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, prod_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, prod_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, quantity INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, date DATE DEFAULT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, em_username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livraison (id_livraison INT AUTO_INCREMENT NOT NULL, adresse_depart VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, adresse_arrive VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, etat VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, date_reception DATE DEFAULT NULL, personneId INT DEFAULT NULL, commandeId INT DEFAULT NULL, INDEX fk_personneId (personneId), INDEX fkdf (commandeId), PRIMARY KEY(id_livraison)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, command_id INT NOT NULL, rating INT NOT NULL, INDEX FK_COMMAND (command_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_COMMAND FOREIGN KEY (command_id) REFERENCES commande (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_product DROP FOREIGN KEY FK_29F0C02CF77D927C');
        $this->addSql('ALTER TABLE panier_product DROP FOREIGN KEY FK_29F0C02C4584665A');
        $this->addSql('ALTER TABLE product_rating DROP FOREIGN KEY FK_BAF567864584665A');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE panier_product');
        $this->addSql('DROP TABLE product_rating');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE categorie CHANGE nom nom VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE panier ADD productId INT DEFAULT NULL, ADD personneId INT NOT NULL, CHANGE prix_total prix_total DOUBLE PRECISION NOT NULL');
        $this->addSql('CREATE INDEX personneId ON panier (personneId)');
        $this->addSql('CREATE INDEX fk_productId ON panier (productId)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9F34925F');
        $this->addSql('DROP INDEX IDX_D34A04AD9F34925F ON product');
        $this->addSql('ALTER TABLE product DROP id_categorie_id, CHANGE prod_name prod_name VARCHAR(255) DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL, CHANGE stock stock INT DEFAULT NULL, CHANGE price price NUMERIC(10, 2) DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495568B7AE87');
        $this->addSql('DROP INDEX IDX_42C8495568B7AE87 ON reservation');
        $this->addSql('ALTER TABLE reservation ADD numberPersonnes INT NOT NULL, DROP tableid_id, CHANGE status status VARCHAR(50) NOT NULL, CHANGE customer_name customerName VARCHAR(255) NOT NULL, CHANGE number_personnes personneId INT NOT NULL, CHANGE date_time dateTime DATE NOT NULL');
        $this->addSql('CREATE INDEX fk_personneIdx ON reservation (personneId)');
        $this->addSql('ALTER TABLE restaurant_table ADD reservationId INT DEFAULT NULL, CHANGE available available TINYINT(1) NOT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant_table ADD CONSTRAINT fk_reservationId FOREIGN KEY (reservationId) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX fk_reservationId ON restaurant_table (reservationId)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON `user`');
        $this->addSql('DROP INDEX IDX_8D93D6495E237E06A625945BE7927C74 ON `user`');
        $this->addSql('ALTER TABLE `user` ADD role VARCHAR(255) NOT NULL, DROP roles, DROP image, DROP is_verified, DROP is_active, CHANGE email email VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(40) NOT NULL, CHANGE prenom prenom VARCHAR(30) NOT NULL, CHANGE num_tel num_tel INT NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE token token VARCHAR(255) NOT NULL');
    }
}
