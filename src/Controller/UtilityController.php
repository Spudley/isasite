<?php

namespace App\Controller;

use App\Entity\Enum\Status;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Service\AccountBalanceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * developer tools.
 */
class UtilityController extends AbstractController
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

    #[Route('/utility/raw-data')]
    public function rawData(AccountBalanceService $accountBalanceService): Response
    {
        $data = $accountBalanceService->getRawData();
        return new Response(json_encode($data), 200, ['Content-Type' => 'application/json']);
    }


    /*
     * Rather than having an actual cron job for this demo, we'll use this
     * to trigger pending actions manually.
     */
    #[Route('/utility/manual-cron')]
    public function manualCron()
    {

    }
}
