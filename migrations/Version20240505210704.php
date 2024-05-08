<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505210704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, quantity INT DEFAULT NULL, prix_total DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, id_categorie_id INT DEFAULT NULL, prod_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, stock VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_D34A04AD9F34925F (id_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD9F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE panier_product ADD CONSTRAINT FK_29F0C02CF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_product ADD CONSTRAINT FK_29F0C02C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_rating ADD CONSTRAINT FK_BAF567864584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier_product DROP FOREIGN KEY FK_29F0C02CF77D927C');
        $this->addSql('ALTER TABLE panier_product DROP FOREIGN KEY FK_29F0C02C4584665A');
        $this->addSql('ALTER TABLE product_rating DROP FOREIGN KEY FK_BAF567864584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD9F34925F');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE product');
    }
}
