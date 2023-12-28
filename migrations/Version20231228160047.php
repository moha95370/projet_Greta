<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231228160047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type_request_id INT NOT NULL, display_name VARCHAR(30) NOT NULL, email VARCHAR(150) NOT NULL, phone VARCHAR(30) NOT NULL, content LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_639A9195A76ED395 (user_id), INDEX IDX_639A91955F7D6E80 (type_request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_request ADD CONSTRAINT FK_639A9195A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_request ADD CONSTRAINT FK_639A91955F7D6E80 FOREIGN KEY (type_request_id) REFERENCES type_request (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_request DROP FOREIGN KEY FK_639A9195A76ED395');
        $this->addSql('ALTER TABLE user_request DROP FOREIGN KEY FK_639A91955F7D6E80');
        $this->addSql('DROP TABLE user_request');
    }
}
