<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212224809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE charge (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', duration DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE charge_station (charge_id INT NOT NULL, station_id INT NOT NULL, INDEX IDX_CBD20C1455284914 (charge_id), INDEX IDX_CBD20C1421BDB235 (station_id), PRIMARY KEY(charge_id, station_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE charge_vehicle (charge_id INT NOT NULL, vehicle_id INT NOT NULL, INDEX IDX_4F6B102355284914 (charge_id), INDEX IDX_4F6B1023545317D1 (vehicle_id), PRIMARY KEY(charge_id, vehicle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE charge_station ADD CONSTRAINT FK_CBD20C1455284914 FOREIGN KEY (charge_id) REFERENCES charge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE charge_station ADD CONSTRAINT FK_CBD20C1421BDB235 FOREIGN KEY (station_id) REFERENCES station (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE charge_vehicle ADD CONSTRAINT FK_4F6B102355284914 FOREIGN KEY (charge_id) REFERENCES charge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE charge_vehicle ADD CONSTRAINT FK_4F6B1023545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge_station DROP FOREIGN KEY FK_CBD20C1455284914');
        $this->addSql('ALTER TABLE charge_station DROP FOREIGN KEY FK_CBD20C1421BDB235');
        $this->addSql('ALTER TABLE charge_vehicle DROP FOREIGN KEY FK_4F6B102355284914');
        $this->addSql('ALTER TABLE charge_vehicle DROP FOREIGN KEY FK_4F6B1023545317D1');
        $this->addSql('DROP TABLE charge');
        $this->addSql('DROP TABLE charge_station');
        $this->addSql('DROP TABLE charge_vehicle');
    }
}
