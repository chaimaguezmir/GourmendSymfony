<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417052434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY cl');
        $this->addSql('DROP INDEX tableid ON reservation');
        $this->addSql('ALTER TABLE reservation ADD tableid_id INT DEFAULT NULL, DROP tableid');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495568B7AE87 FOREIGN KEY (tableid_id) REFERENCES restaurant_table (id)');
        $this->addSql('CREATE INDEX IDX_42C8495568B7AE87 ON reservation (tableid_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, age INT NOT NULL, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, num_tel INT DEFAULT NULL, adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495568B7AE87');
        $this->addSql('DROP INDEX IDX_42C8495568B7AE87 ON reservation');
        $this->addSql('ALTER TABLE reservation ADD tableid INT NOT NULL, DROP tableid_id');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT cl FOREIGN KEY (tableid) REFERENCES restaurant_table (id)');
        $this->addSql('CREATE INDEX tableid ON reservation (tableid)');
    }
}
