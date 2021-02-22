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
            ->select('k.id', 'k.starts_at', 'k.topic')
            ->from('klass', 'k')
        ;

        $klassesData = $this->connection->fetchAllAssociative($queryBuilder->getSQL(), $queryBuilder->getParameters());

        $studentsData = $this->connection->getWrappedConnection()
            ->query('SELECT * FROM klass_user')
            ->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_ASSOC)
        ;

        return array_map(function (array $klassData) use ($studentsData) {
            $klassId = (int) $klassData['id'];
            $studentIds = $studentsData[$klassId] ?? [];

            return new KlassView(
                $klassId,
                new \DateTimeImmutable($klassData['starts_at']),
                $klassData['topic'],
                array_map(function (array $studentId) {
                    return ['id' => (int) $studentId['user_id']];
                }, $studentIds)
            );
        }, $klassesData);
    }
}
