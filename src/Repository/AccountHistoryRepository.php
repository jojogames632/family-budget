<?php

namespace App\Repository;

use App\Entity\AccountHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountHistory[]    findAll()
 * @method AccountHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountHistory::class);
    }

    public function findByAccountAndDates($account, $startDate, $endDate)
    {
        return $this->createQueryBuilder('a')
            ->where('a.account = :account')
            ->andWhere('a.lastDayDate BETWEEN :startDate AND :endDate')
            ->setParameters([
                ':account' => $account,
                ':startDate' => $startDate,
                ':endDate' => $endDate
            ])
            ->orderBy('a.lastDayDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
