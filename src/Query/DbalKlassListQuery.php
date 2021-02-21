<?php

namespace App\Query;

use Doctrine\DBAL\Driver\Connection;

class DbalKlassListQuery implements KlassListQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /** @return KlassView[] */
    public function getAll(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('k.id', 'k.topic')
            ->from('klass', 'k')
        ;

        $klassData = $this->connection->fetchAll($queryBuilder->getSQL(), $queryBuilder->getParameters());

        return array_map(function (array $klassData) {
            return new KlassView((int) $klassData['id']);
        }, $klassData);
    }
}
