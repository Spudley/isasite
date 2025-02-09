<?php

namespace App\Service;

use App\Entity\Enum\Status;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

class AccountBalanceService
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    /*
     * Provides a list of funds (including 'cash'), and the amount held in each
     * thus providing a full statement of the user's account.
     */
    public function getFullAccountSummary(): array
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select([
                't.fundName',
                'SUM(t.units) as unitTotal',
                'SUM(t.units * t.pencePerUnit) as totalValue',
            ])
            ->from(Transaction::class, 't')
            ->where('t.status = :status')
            ->groupBy('t.fundName')
            ->setParameter('status', Status::COMPLETE);

        return $qb->getQuery()->getArrayResult();
    }
}
