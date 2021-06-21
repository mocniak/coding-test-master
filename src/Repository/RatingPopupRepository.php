<?php

namespace App\Repository;

use App\Entity\RatingPopup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RatingPopup|null find(int $id, $lockMode = null, $lockVersion = null)
 * @method RatingPopup|null findOneBy(array $criteria, array $orderBy = null)
 * @method RatingPopup[]    findAll()
 * @method RatingPopup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingPopupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RatingPopup::class);
    }
}
