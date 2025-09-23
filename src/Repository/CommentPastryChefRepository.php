<?php

namespace App\Repository;

use App\Entity\CommentPastryChef;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentPastryChef>
 *
 * @method CommentPastryChef|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentPastryChef|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentPastryChef[]    findAll()
 * @method CommentPastryChef[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentPastryChefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentPastryChef::class);
    }

//    /**
//     * @return CommentPastryChef[] Returns an array of CommentPastryChef objects
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

//    public function findOneBySomeField($value): ?CommentPastryChef
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
