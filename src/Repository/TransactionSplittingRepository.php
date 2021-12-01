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

    public function getTransactionsSplittingWithMonth($month = null)
    {
        if ($month === null) {
            $month = (int) date('m');
        }

        $year = (int) date('Y');

        $dateMin = new \DateTimeImmutable("$year-$month-01T00:00:00");
        $dateMax = (clone $dateMin)->modify('last day of this month')->setTime(23, 59, 59);

        return $this->createQueryBuilder('ts')
            ->where('ts.bankDate BETWEEN :dateMin AND :dateMax')
            ->setParameters(
                [
                    'dateMin' => $dateMin->format('Y-m-d H:i:s'),
                    'dateMax' => $dateMax->format('Y-m-d H:i:s'),
                ]
            )
            ->orderBy('ts.bankDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
