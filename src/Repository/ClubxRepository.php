<?php

namespace App\Repository;

use App\Entity\Clubx;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Clubx|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clubx|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clubx[]    findAll()
 * @method Clubx[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clubx::class);
    }

    // /**
    //  * @return Clubx[] Returns an array of Clubx objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Clubx
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
