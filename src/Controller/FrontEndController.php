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
class FrontEndController extends AbstractController
{
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