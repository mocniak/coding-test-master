<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200828095157 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE klass (id INT AUTO_INCREMENT NOT NULL, starts_at DATETIME NOT NULL, topic VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE klass_user (klass_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2A39A1F239E510A5 (klass_id), INDEX IDX_2A39A1F2A76ED395 (user_id), PRIMARY KEY(klass_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE klass_user ADD CONSTRAINT FK_2A39A1F239E510A5 FOREIGN KEY (klass_id) REFERENCES klass (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE klass_user ADD CONSTRAINT FK_2A39A1F2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE klass_user DROP FOREIGN KEY FK_2A39A1F239E510A5');
        $this->addSql('DROP TABLE klass');
        $this->addSql('DROP TABLE klass_user');
    }
}
