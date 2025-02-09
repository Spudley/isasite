<?php

namespace App\Controller;

use App\Entity\Enum\Status;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * developer tools.
 */
class UtilityController
{
    /*
     * A quick way to add a £25000 record to the account balance so that we can test
     * fund orders without getting bogged down with doing bank transfers first.
     */
    #[Route('/utility/add-cash')]
    public function addCash(TransactionRepository $transactionRepository): Response
    {
        // This is dev code intended to just run once; I won't structure it.
        $transaction = new Transaction();
        $transaction->setOurTransactionId(uniqid('bank-', true));
        $transaction->setTheirTransactionId('');
        $transaction->setFundName('cash');
        $transaction->setPencePerUnit(1);
        $transaction->setUnits(2500000);
        $transaction->setStatus(Status::COMPLETE);
        $transactionRepository->save($transaction, true);

        return new Response("<html><body>Added £25k to the account.</body></html>");
    }

    /*
     * Rather than having an actual cron job for this demo, we'll use this
     * to trigger pending actions manually.
     */
    #[Route('/utility/manual-cron')]
    public function manualCron()
    {

    }

    #[Route('/')]
    public function main(AccountBalanceService $accountBalanceService): Response
    {
        $results = $accountBalanceService->getFullAccountSummary();

        return $this->render(
            'main.html.twig',
            ['results'=>$results]
        );
    }

}