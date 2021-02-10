<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add user for testing
 */
final class Version20200811083747 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add user for testing';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('INSERT INTO user (email, password) VALUES (\'user@lingoda.com\', \'$argon2id$v=19$m=65536,t=4,p=1$4iUanuPr42hKCyPRBrqpJA$/T2Y7njmg6MMG9bnWRnnmsPDQkO/TDwdCAtEcvUkIZo\')');
    }
}
