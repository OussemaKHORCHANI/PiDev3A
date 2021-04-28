<?php

namespace App\Repository;

use App\Entity\Clubcomp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Clubcomp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clubcomp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clubcomp[]    findAll()
 * @method Clubcomp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClubcompRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clubcomp::class);
    }

    // /**
    //  * @return Clubcomp[] Returns an array of Clubcomp objects
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
    public function findOneBySomeField($value): ?Clubcomp
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
