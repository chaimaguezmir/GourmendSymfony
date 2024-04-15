<?php

namespace App\Repository;

use App\Entity\Commande;
use App\Entity\Livraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

//    /**
//     * @return Commande[] Returns an array of Commande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commande
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findCommandesSansLivraison()
{
    return $this->createQueryBuilder('c')
        ->leftJoin('c.livraison', 'l')
        ->where('l.id IS NULL')
        ->getQuery()
        ->getResult();
}
    /**
     * @param Livraison $livraison
     * @return Commande[]
     */
    public function findCommandesWithLivraison(Livraison $livraison): array
    {
        // Sélectionner les commandes avec une livraison spécifique et celles sans livraison
        return $this->createQueryBuilder('c')
            ->leftJoin('c.livraison', 'l')
            ->where('l.id = :livraisonId OR l.id IS NULL')
            ->setParameter('livraisonId', $livraison->getId())
            ->getQuery()
            ->getResult();
    }
    public function countByStatus($status)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
