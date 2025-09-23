<?php

namespace App\Repository;

use App\Entity\CommentPastry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentPastry>
 *
 * @method CommentPastry|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentPastry|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentPastry[]    findAll()
 * @method CommentPastry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentPastryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentPastry::class);
    }

//    /**
//     * @return CommentPastry[] Returns an array of CommentPastry objects
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

//    public function findOneBySomeField($value): ?CommentPastry
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
