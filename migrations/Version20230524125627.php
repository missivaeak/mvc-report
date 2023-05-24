<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230524125627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE obstacle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, difficulty_int INTEGER DEFAULT NULL, difficulty_str INTEGER DEFAULT NULL, difficulty_dex INTEGER DEFAULT NULL, cost_reward_time INTEGER NOT NULL, cost_reward_health INTEGER NOT NULL, cost_reward_stamina INTEGER NOT NULL, cost_reward_int INTEGER NOT NULL, cost_reward_str INTEGER NOT NULL, cost_reward_dex INTEGER NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE obstacle');
    }
}
