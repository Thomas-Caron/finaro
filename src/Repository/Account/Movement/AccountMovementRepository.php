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

    public function getLastIncomes(Account $account): array
    {
        $movementType = $this->getEntityManager()->getRepository(AccountMovementType::class)->findOneBy([
                'slug' => AccountMovementType::INCOMES
        ]);
        
        $last = $this->createQueryBuilder('a')
            ->where('a.account = :account')
            ->andWhere('a.movementType = :movementType')
            ->setParameter('account', $account)
            ->setParameter('movementType', $movementType) 
            ->orderBy('a.year', 'DESC')
            ->addOrderBy('a.month', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($last !== null) {
            return $this->createQueryBuilder('a')
                ->where('a.account = :account')
                ->andWhere('a.movementType = :movementType')
                ->andWhere('a.year = :year')
                ->andWhere('a.month = :month')
                ->setParameter('account', $account)
                ->setParameter('movementType', $movementType)
                ->setParameter('year', $last->getYear())
                ->setParameter('month', $last->getMonth())
                ->getQuery()
                ->getResult();
        }

        return [];
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

    public function getLastFixedExpenses(Account $account): array
    {
        $movementType = $this->getEntityManager()->getRepository(AccountMovementType::class)->findOneBy([
                'slug' => AccountMovementType::FIXED_EXPENSES
        ]);

        $last = $this->createQueryBuilder('a')
            ->where('a.account = :account')
            ->andWhere('a.movementType = :movementType')
            ->setParameter('account', $account)
            ->setParameter('movementType', $movementType) 
            ->orderBy('a.year', 'DESC')
            ->addOrderBy('a.month', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($last !== null) {
            return $this->createQueryBuilder('a')
                ->where('a.account = :account')
                ->andWhere('a.movementType = :movementType')
                ->andWhere('a.year = :year')
                ->andWhere('a.month = :month')
                ->setParameter('account', $account)
                ->setParameter('movementType', $movementType)
                ->setParameter('year', $last->getYear())
                ->setParameter('month', $last->getMonth())
                ->getQuery()
                ->getResult();
        }

        return [];
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

    public function getMonthsWithMovementsByYear(Account $account, Year $year): array
    {
        $monthIds = $this->createQueryBuilder('a')
            ->select('DISTINCT IDENTITY(a.month)')
            ->where('a.account = :account')
            ->andWhere('a.year = :year')
            ->setParameter('account', $account)
            ->setParameter('year', $year)
            ->groupBy('a.month')
            ->getQuery()
            ->getSingleColumnResult();

        $queryBuilder = $this->getEntityManager()->getRepository(Month::class)->createQueryBuilder('m');
        $queryBuilder->where('m.id IN (:monthsIds)')
            ->setParameter('monthsIds', $monthIds)
            ->orderBy('m.position', 'ASC')
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function getMonthsWithoutMovementsByYear(Account $account, Year $year): array
    {
        $initialMonth = $this->createQueryBuilder('a')
            ->select('IDENTITY(a.month) as monthId')
            ->where('a.account = :account')
            ->andWhere('a.year = :year')
            ->andWhere('a.description = :initialDesc')
            ->setParameter('account', $account)
            ->setParameter('year', $year)
            ->setParameter('initialDesc', 'initial')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        $initialMonthId = $initialMonth['monthId'] ?? null;

        $initialMonth = $this->getEntityManager()->getRepository(Month::class)->createQueryBuilder('m')
            ->where('m.id = :initialMonthId')
            ->setParameter('initialMonthId', $initialMonthId)
            ->getQuery()
            ->getOneOrNullResult();

        $monthIds = $this->createQueryBuilder('a')
            ->select('DISTINCT IDENTITY(a.month)')
            ->where('a.account = :account')
            ->andWhere('a.year = :year')
            ->setParameter('account', $account)
            ->setParameter('year', $year)
            ->groupBy('a.month')
            ->getQuery()
            ->getSingleColumnResult();

        $excludeIds = $monthIds;
        if ($initialMonth !== null) {
            $excludeIds[] = $initialMonth->getId();
        }

        $queryBuilder = $this->getEntityManager()->getRepository(Month::class)->createQueryBuilder('m');

        if (!empty($excludeIds)) {
            $queryBuilder->where('m.id NOT IN (:excludeIds)')
                ->setParameter('excludeIds', $excludeIds);
            if ($initialMonth !== null) {
                $queryBuilder->andWhere('m.position >= :initialPosition')
                    ->setParameter('initialPosition', $initialMonth->getPosition());
            }
        }

        $queryBuilder->orderBy('m.position', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}
