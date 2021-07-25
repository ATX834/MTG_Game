<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210722233143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, power INT NOT NULL, toughness INT NOT NULL, health_point INT NOT NULL, speed INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_spell (character_id INT NOT NULL, spell_id INT NOT NULL, INDEX IDX_2EFC2AEF1136BE75 (character_id), INDEX IDX_2EFC2AEF479EC90D (spell_id), PRIMARY KEY(character_id, spell_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_color (character_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_9895AF8B1136BE75 (character_id), INDEX IDX_9895AF8B7ADA1FB5 (color_id), PRIMARY KEY(character_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fighter (id INT AUTO_INCREMENT NOT NULL, personnage_id INT NOT NULL, power INT NOT NULL, toughness INT NOT NULL, health_point INT NOT NULL, mana_base INT NOT NULL, speed INT NOT NULL, player VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, max_health_point INT NOT NULL, INDEX IDX_7A08C3FC5E315342 (personnage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fighter_spell (fighter_id INT NOT NULL, spell_id INT NOT NULL, INDEX IDX_A30AF1134934341 (fighter_id), INDEX IDX_A30AF11479EC90D (spell_id), PRIMARY KEY(fighter_id, spell_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fighter_color (fighter_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_BC592A7534934341 (fighter_id), INDEX IDX_BC592A757ADA1FB5 (color_id), PRIMARY KEY(fighter_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spell (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, mana_cost INT NOT NULL, power INT DEFAULT NULL, toughness INT DEFAULT NULL, speed INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spell_color (spell_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_7A0452DE479EC90D (spell_id), INDEX IDX_7A0452DE7ADA1FB5 (color_id), PRIMARY KEY(spell_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_spell ADD CONSTRAINT FK_2EFC2AEF1136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_spell ADD CONSTRAINT FK_2EFC2AEF479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_color ADD CONSTRAINT FK_9895AF8B1136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_color ADD CONSTRAINT FK_9895AF8B7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fighter ADD CONSTRAINT FK_7A08C3FC5E315342 FOREIGN KEY (personnage_id) REFERENCES `character` (id)');
        $this->addSql('ALTER TABLE fighter_spell ADD CONSTRAINT FK_A30AF1134934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fighter_spell ADD CONSTRAINT FK_A30AF11479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fighter_color ADD CONSTRAINT FK_BC592A7534934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fighter_color ADD CONSTRAINT FK_BC592A757ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE spell_color ADD CONSTRAINT FK_7A0452DE479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE spell_color ADD CONSTRAINT FK_7A0452DE7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_spell DROP FOREIGN KEY FK_2EFC2AEF1136BE75');
        $this->addSql('ALTER TABLE character_color DROP FOREIGN KEY FK_9895AF8B1136BE75');
        $this->addSql('ALTER TABLE fighter DROP FOREIGN KEY FK_7A08C3FC5E315342');
        $this->addSql('ALTER TABLE character_color DROP FOREIGN KEY FK_9895AF8B7ADA1FB5');
        $this->addSql('ALTER TABLE fighter_color DROP FOREIGN KEY FK_BC592A757ADA1FB5');
        $this->addSql('ALTER TABLE spell_color DROP FOREIGN KEY FK_7A0452DE7ADA1FB5');
        $this->addSql('ALTER TABLE fighter_spell DROP FOREIGN KEY FK_A30AF1134934341');
        $this->addSql('ALTER TABLE fighter_color DROP FOREIGN KEY FK_BC592A7534934341');
        $this->addSql('ALTER TABLE character_spell DROP FOREIGN KEY FK_2EFC2AEF479EC90D');
        $this->addSql('ALTER TABLE fighter_spell DROP FOREIGN KEY FK_A30AF11479EC90D');
        $this->addSql('ALTER TABLE spell_color DROP FOREIGN KEY FK_7A0452DE479EC90D');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE character_spell');
        $this->addSql('DROP TABLE character_color');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE fighter');
        $this->addSql('DROP TABLE fighter_spell');
        $this->addSql('DROP TABLE fighter_color');
        $this->addSql('DROP TABLE spell');
        $this->addSql('DROP TABLE spell_color');
    }
}
