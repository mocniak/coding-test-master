<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200828123412 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(<<<SQL
            INSERT INTO klass (starts_at, topic) 
            VALUES 
                   ('2020-09-09 12:00', 'Hello and Goodbye'),  
                   ('2020-09-12 15:00', 'Animal names'),
                   ('2020-09-15 06:00', 'Getting around')
            ;  
        SQL);
    }
}
