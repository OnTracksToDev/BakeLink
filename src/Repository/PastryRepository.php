<?php

namespace App\Repository;

use App\Entity\Pastry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pastry>
 *
 * @method Pastry|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pastry|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pastry[]    findAll()
 * @method Pastry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PastryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pastry::class);
    }

//    /**
//     * @return Pastry[] Returns an array of Pastry objects
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

//    public function findOneBySomeField($value): ?Pastry
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
