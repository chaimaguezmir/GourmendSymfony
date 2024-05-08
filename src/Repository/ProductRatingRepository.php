<?php

namespace App\Repository;

use App\Entity\ProductRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductRating>
 *
 * @method ProductRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductRating[]    findAll()
 * @method ProductRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductRating::class);
    }
/**
 * Calculate statistics of products based on their ratings.
 *
 * @return array
 */
public function calculateRatingStatistics(): array
{
    return $this->createQueryBuilder('p')
        ->select('p.prod_name', 'AVG(pr.nbrratting) as averageRating', 'COUNT(pr.id) as totalRatings')
        ->leftJoin('p.productRatings', 'pr')
        ->groupBy('p.id')
        ->having('COUNT(pr.id) > 0') // Exclut les produits sans évaluation
        ->getQuery()
        ->getResult();
}
//    /**
//     * @return ProductRating[] Returns an array of ProductRating objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductRating
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
