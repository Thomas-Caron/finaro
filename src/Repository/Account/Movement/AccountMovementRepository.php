<?php

declare(strict_types=1);

namespace App\Repository\Account\Movement;

use App\Entity\Account\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Account\Movement\AccountMovement;
use App\Entity\Account\Movement\AccountMovementType;
use App\Entity\Date\Month;
use App\Entity\Date\Year;

/**
 * @extends ServiceEntityRepository<AccountMovement>
 */
class AccountMovementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountMovement::class);
    }

    //    /**
    //     * @return AccountMovement[] Returns an array of AccountMovement objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AccountMovement
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getRemainingPrevious(Account $account, Month $month, Year $year): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.account = :account')
            ->andWhere('a.month = :month')
            ->andWhere('a.year = :year')
            ->andWhere('a.movementType = :movementType')
            ->setParameter('account', $account)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('movementType', $this->getEntityManager()->getRepository(AccountMovementType::class)->findOneBy(
                ['slug' => AccountMovementType::REMAINING_PREVIOUS]
            ))
            ->getQuery()
            ->getResult();
    }

    public function getIncomes(Account $account, Month $month, Year $year): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.account = :account')
            ->andWhere('a.month = :month')
            ->andWhere('a.year = :year')
            ->andWhere('a.movementType = :movementType')
            ->setParameter('account', $account)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('movementType', $this->getEntityManager()->getRepository(AccountMovementType::class)->findOneBy(
                ['slug' => AccountMovementType::INCOMES]
            ))
            ->getQuery()
            ->getResult();
    }

    public function getFixedExpenses(Account $account, Month $month, Year $year): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.account = :account')
            ->andWhere('a.month = :month')
            ->andWhere('a.year = :year')
            ->andWhere('a.movementType = :movementType')
            ->setParameter('account', $account)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('movementType', $this->getEntityManager()->getRepository(AccountMovementType::class)->findOneBy(
                ['slug' => AccountMovementType::FIXED_EXPENSES]
            ))
            ->getQuery()
            ->getResult();
    }

    public function getExpenses(Account $account, Month $month, Year $year): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.account = :account')
            ->andWhere('a.month = :month')
            ->andWhere('a.year = :year')
            ->andWhere('a.movementType = :movementType')
            ->setParameter('account', $account)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('movementType', $this->getEntityManager()->getRepository(AccountMovementType::class)->findOneBy(
                ['slug' => AccountMovementType::EXPENSES]
            ))
            ->getQuery()
            ->getResult();
    }
}
