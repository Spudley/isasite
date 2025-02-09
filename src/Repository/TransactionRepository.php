<?php

namespace App\Repository;

use App\Entity\Enum\Status;
use App\Entity\Transaction;
use App\Service\FundNegotiator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function save(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Transaction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createNewOrderTransaction(FundNegotiator $negotiator): Transaction
    {
        $transaction = new Transaction();
        $transaction->setOurTransactionId($negotiator->ourTransactionId);
        $transaction->setTheirTransactionId($negotiator->theirTransactionId);
        $transaction->setFundName($negotiator->fund);
        $transaction->setPencePerUnit($negotiator->unitPrice);
        $transaction->setUnits($negotiator->units);
        $transaction->setStatus(Status::PENDING);
        return $transaction;
    }
}