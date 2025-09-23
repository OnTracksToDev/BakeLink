<?php

namespace App\Repository;

use App\Entity\RequestOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RequestOrder>
 *
 * @method RequestOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestOrder[]    findAll()
 * @method RequestOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestOrder::class);
    }

//    /**
//     * @return RequestOrder[] Returns an array of RequestOrder objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RequestOrder
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
