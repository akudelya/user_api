<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018064716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_to_group DROP FOREIGN KEY FK_867191F5A76ED395');
        $this->addSql('ALTER TABLE user_to_group DROP FOREIGN KEY FK_867191F5FE54D947');
        $this->addSql('DROP TABLE user_to_group');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_to_group (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, group_id INT DEFAULT NULL, INDEX IDX_867191F5A76ED395 (user_id), INDEX IDX_867191F5FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_to_group ADD CONSTRAINT FK_867191F5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_to_group ADD CONSTRAINT FK_867191F5FE54D947 FOREIGN KEY (group_id) REFERENCES groups (id)');
    }
}
