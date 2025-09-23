<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Message;
use App\Entity\PastryChef;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }
    public function findByClientRequests(Client $client): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.requestOrder', 'ro')
            ->where('ro.client = :client')
            ->setParameter('client', $client)
            ->getQuery()
            ->getResult();
    }
    public function findByPastryChefRequests(PastryChef $pastryChef): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.requestOrder', 'ro')
            ->where('ro.pastryChef = :pastryChef')
            ->setParameter('pastryChef', $pastryChef)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Message[] Returns an array of Message objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
