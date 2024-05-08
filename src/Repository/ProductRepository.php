<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
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
//     * @return Product[] Returns an array of Product objects
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

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findByCategoryId(int $categoryId): array
{
    return $this->createQueryBuilder('p')
        ->join('p.idCategorie', 'c')
        ->andWhere('c.id = :categoryId')
        ->setParameter('categoryId', $categoryId)
        ->getQuery()
        ->getResult();
}


/**
     * return Product[]
     */
   
     public function findprodbyNom($nom)
     {
         return $this->createQueryBuilder('cat')
             ->where('cat.nom LIKE :nom')
             ->setParameter('nom', '%'.$nom.'%')
             ->getQuery()
             ->getResult();
     }

     public function trieprod()
    {
        return $this->createQueryBuilder('prod')
            ->orderBy('prod.price', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function trieproddes()
    {
        return $this->createQueryBuilder('prod') //mtaa requette doctrine
            ->orderBy('prod.price', 'DESC') 
            ->getQuery()
            ->getResult();
    }


}
