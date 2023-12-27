<?php

namespace App\Repository;

use App\Entity\Charge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Charge>
 *
 * @method Charge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Charge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Charge[]    findAll()
 * @method Charge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChargeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Charge::class);
    }

//    /**
//     * @return Charge[] Returns an array of Charge objects
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

    //requette pour réccupéré les recharges des utilisateur connecter
    // public function findCharges(): array
    // {
    //     $entityManager = $this->getEntityManager();

    //     $query = $entityManager->createQuery(
    //         'SELECT c.id, c.createdAt, c.duration, c.price, c.payment
    //         FROM App\Entity\Charge c
    //         ORDER BY c.createdAt DESC'
    //     )
    //     ;
    //     return $query->getResult();
    // }

    // public function findCharges(): array
    // {
    //     $entityManager = $this->getEntityManager();

    //     $query = $entityManager->createQuery(
    //         'SELECT c.id, c.createdAt, c.duration, c.price, c.payment
    //         FROM App\Entity\Charge c
    //         INNER JOIN App\Entity\Vehicle v ON c.vehicle = v.id
    //         INNER JOIN App\Entity\User u ON v.user = u.id
    //         WHERE u.id = :userId'
    //     )
    //     ;
    //     return $query->getResult();
    // }



    public function findChargesUser($userId)
    {
        return $this->createQueryBuilder('c')
            ->join('c.vehicle', 'v')
            ->join('v.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Charge
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
