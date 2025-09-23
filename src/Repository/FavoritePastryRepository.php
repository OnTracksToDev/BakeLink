<?php

namespace App\Repository;

use App\Entity\FavoritePastry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoritePastry>
 *
 * @method FavoritePastry|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoritePastry|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoritePastry[]    findAll()
 * @method FavoritePastry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoritePastryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoritePastry::class);
    }

//    /**
//     * @return FavoritePastry[] Returns an array of FavoritePastry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FavoritePastry
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
