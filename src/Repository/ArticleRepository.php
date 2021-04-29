<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByref( $ref): ?Article
    {
        return $this->createQueryBuilder('a')
            ->where("a.ref = ?1")
            ->setParameter(1, $ref)
            ->getQuery()
            ->getOneOrNullResult()

            ;
    }
    public function OrderByQt()
    {
        return $this->createQueryBuilder('article')
            ->orderBy('article.qtArticle','DESC')
            ->getQuery()
            ->getResult();
    }
    public function findStudentByfield($libelle){
        return $this->createQueryBuilder('article')
            ->Where('article.libelle LIKE :libelle')
            ->setParameter('libelle', '%'.$libelle.'%')
            ->getQuery()
            ->getResult();
    }
    public function a($libelle){
        return $this->createQueryBuilder('article')
            ->where('article.libelle LIKE :libelle')
            ->setParameter('libelle', '%'.$libelle.'%')
            ->getQuery()
            ->getResult();
    }
    public function findItemsCreatedBetweenTwoDates(float $beginprix,float $endprix)
    {
        return $this->createQueryBuilder('a')
            ->where("a.prix >= ?1")
            ->andWhere("a.prix <= ?2")
            ->setParameter(1, $beginprix)
            ->setParameter(2, $endprix)
            ->getQuery()
            ->getResult();
    }
    public function findStudentByNsc($libelle){

        return $this->createQueryBuilder('article')
            ->where('article.libelle LIKE :libelle')
            ->setParameter('libelle', '%'.$libelle.'%')
            ->getQuery()
            ->getResult();


    }
    public function getART()
    {

        $qb = $this->createQueryBuilder('v')
            ->select('COUNT(v.id_article) AS art, SUBSTRING(v.rate, 1, 10) AS prixart')
            ->groupBy('prixart');
        return $qb->getQuery()
            ->getResult();

    }
}
