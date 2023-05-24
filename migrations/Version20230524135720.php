<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230524135720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__obstacle AS SELECT id, name, description, difficulty_int, difficulty_str, difficulty_dex, cost_reward_time, cost_reward_health, cost_reward_stamina, cost_reward_int, cost_reward_str, cost_reward_dex, cost_reward_lck, cost_reward_spd, cost_reward_con FROM obstacle');
        $this->addSql('DROP TABLE obstacle');
        $this->addSql('CREATE TABLE obstacle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, difficulty_int INTEGER DEFAULT NULL, difficulty_str INTEGER DEFAULT NULL, difficulty_dex INTEGER DEFAULT NULL, cost_reward_time INTEGER NOT NULL, cost_reward_health INTEGER NOT NULL, cost_reward_stamina INTEGER NOT NULL, cost_reward_int INTEGER NOT NULL, cost_reward_str INTEGER NOT NULL, cost_reward_dex INTEGER NOT NULL, cost_reward_lck INTEGER NOT NULL, cost_reward_spd INTEGER NOT NULL, cost_reward_con INTEGER NOT NULL)');
        $this->addSql('INSERT INTO obstacle (id, name, description, difficulty_int, difficulty_str, difficulty_dex, cost_reward_time, cost_reward_health, cost_reward_stamina, cost_reward_int, cost_reward_str, cost_reward_dex, cost_reward_lck, cost_reward_spd, cost_reward_con) SELECT id, name, description, difficulty_int, difficulty_str, difficulty_dex, cost_reward_time, cost_reward_health, cost_reward_stamina, cost_reward_int, cost_reward_str, cost_reward_dex, cost_reward_lck, cost_reward_spd, cost_reward_con FROM __temp__obstacle');
        $this->addSql('DROP TABLE __temp__obstacle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__obstacle AS SELECT id, name, description, difficulty_int, difficulty_str, difficulty_dex, cost_reward_time, cost_reward_health, cost_reward_stamina, cost_reward_int, cost_reward_str, cost_reward_dex, cost_reward_lck, cost_reward_spd, cost_reward_con FROM obstacle');
        $this->addSql('DROP TABLE obstacle');
        $this->addSql('CREATE TABLE obstacle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, difficulty_int INTEGER DEFAULT NULL, difficulty_str INTEGER DEFAULT NULL, difficulty_dex INTEGER DEFAULT NULL, cost_reward_time INTEGER NOT NULL, cost_reward_health INTEGER NOT NULL, cost_reward_stamina INTEGER NOT NULL, cost_reward_int INTEGER NOT NULL, cost_reward_str INTEGER NOT NULL, cost_reward_dex INTEGER NOT NULL, cost_reward_lck INTEGER DEFAULT 0 NOT NULL, cost_reward_spd INTEGER DEFAULT 0 NOT NULL, cost_reward_con INTEGER DEFAULT 0 NOT NULL)');
        $this->addSql('INSERT INTO obstacle (id, name, description, difficulty_int, difficulty_str, difficulty_dex, cost_reward_time, cost_reward_health, cost_reward_stamina, cost_reward_int, cost_reward_str, cost_reward_dex, cost_reward_lck, cost_reward_spd, cost_reward_con) SELECT id, name, description, difficulty_int, difficulty_str, difficulty_dex, cost_reward_time, cost_reward_health, cost_reward_stamina, cost_reward_int, cost_reward_str, cost_reward_dex, cost_reward_lck, cost_reward_spd, cost_reward_con FROM __temp__obstacle');
        $this->addSql('DROP TABLE __temp__obstacle');
    }
}
