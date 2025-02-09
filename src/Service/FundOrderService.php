<?php

namespace App\Service;

use App\Entity\Enum\Status;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;

class FundOrderService
{
    public function __construct(
        protected TransactionRepository $transactionRepository,
        protected FundNegotiator $negotiator,
    )
    {
    }
    public function initiateOrder(int $amount, string $fund): array
    {
        try {
            $ourTransactionId = uniqid('isa-', true);
            $this->negotiator->negotiate($amount, $fund, $ourTransactionId);
            $balancingNegotiator = $this->negotiator->createBalancingCashNegotiator();
            $creditTransaction = $this->transactionRepository->createNewOrderTransaction($this->negotiator);
            $debitTransaction = $this->transactionRepository->createNewOrderTransaction($balancingNegotiator);
            $this->transactionRepository->save($creditTransaction);
            $this->transactionRepository->save($debitTransaction, true);

            return ['success' => true, 'message' => "Fund {$fund} has accepted your order request."];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}