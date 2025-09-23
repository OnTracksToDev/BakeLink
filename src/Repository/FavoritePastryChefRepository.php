<?php

namespace App\Repository;

use App\Entity\FavoritePastryChef;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoritePastryChef>
 *
 * @method FavoritePastryChef|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoritePastryChef|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoritePastryChef[]    findAll()
 * @method FavoritePastryChef[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoritePastryChefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoritePastryChef::class);
    }

//    /**
//     * @return FavoritePastryChef[] Returns an array of FavoritePastryChef objects
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

//    public function findOneBySomeField($value): ?FavoritePastryChef
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
