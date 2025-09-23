<?php

namespace App\Repository;

use App\Entity\PastryChef;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PastryChef>
 *
 * @method PastryChef|null find($id, $lockMode = null, $lockVersion = null)
 * @method PastryChef|null findOneBy(array $criteria, array $orderBy = null)
 * @method PastryChef[]    findAll()
 * @method PastryChef[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PastryChefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PastryChef::class);
    }
    public function findByCity($city)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.city) = :city')
            ->setParameter('city', strtolower($city))
            ->getQuery()
            ->getResult();
    }
        //    /**
//     * @return PastryChef[] Returns an array of PastryChef objects
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

//    public function findOneBySomeField($value): ?PastryChef
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
