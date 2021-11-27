<?php

namespace App\Repository;

use App\Entity\TransactionSplitting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransactionSplitting|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionSplitting|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionSplitting[]    findAll()
 * @method TransactionSplitting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionSplittingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionSplitting::class);
    }

    // /**
    //  * @return TransactionSplitting[] Returns an array of TransactionSplitting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransactionSplitting
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
