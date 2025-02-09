<?php
namespace App\Controller;

use App\Service\FundOrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FundController extends AbstractController
{
    //I know it should be a POST request type here, but I'll use GET for this demo so I can more easily call it from a browser.
    #[Route('/api/initiate-order', name: 'fund_initiate_order', methods: ['GET'])]
    public function initiateOrder(Request $request, FundOrderService $fundOrder): Response
    {
        //@todo add input validation/sanitisation/filtering.
        $data = $request->request->all();

        $result = $fundOrder->initiateOrder($data['amount'] ?? 0, $data['fund'] ?? 0);

        return new Response(
            json_encode($result),
            $result['success'] ? 200 : 400,
            ['Content-Type' => 'application/json']);
    }
}
