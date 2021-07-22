<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210721232605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fighter (id INT AUTO_INCREMENT NOT NULL, personnage_id INT NOT NULL, power INT NOT NULL, toughness INT NOT NULL, health_point INT NOT NULL, mana_base INT NOT NULL, speed INT NOT NULL, INDEX IDX_7A08C3FC5E315342 (personnage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fighter_spell (fighter_id INT NOT NULL, spell_id INT NOT NULL, INDEX IDX_A30AF1134934341 (fighter_id), INDEX IDX_A30AF11479EC90D (spell_id), PRIMARY KEY(fighter_id, spell_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fighter_color (fighter_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_BC592A7534934341 (fighter_id), INDEX IDX_BC592A757ADA1FB5 (color_id), PRIMARY KEY(fighter_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fighter ADD CONSTRAINT FK_7A08C3FC5E315342 FOREIGN KEY (personnage_id) REFERENCES `character` (id)');
        $this->addSql('ALTER TABLE fighter_spell ADD CONSTRAINT FK_A30AF1134934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fighter_spell ADD CONSTRAINT FK_A30AF11479EC90D FOREIGN KEY (spell_id) REFERENCES spell (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fighter_color ADD CONSTRAINT FK_BC592A7534934341 FOREIGN KEY (fighter_id) REFERENCES fighter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fighter_color ADD CONSTRAINT FK_BC592A757ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fighter_spell DROP FOREIGN KEY FK_A30AF1134934341');
        $this->addSql('ALTER TABLE fighter_color DROP FOREIGN KEY FK_BC592A7534934341');
        $this->addSql('DROP TABLE fighter');
        $this->addSql('DROP TABLE fighter_spell');
        $this->addSql('DROP TABLE fighter_color');
    }
}
