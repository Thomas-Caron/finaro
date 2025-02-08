<?php

declare(strict_types=1);

namespace Finaro\Controller\App\Simulator;

use Finaro\Entity\Simulator\MortgateLoanData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[
    Route('/simulation/credit-immobilier', name: 'finaro_app_simulator_mortgage_loan'),
    IsGranted('IS_AUTHENTICATED_FULLY')
]
class MortgageLoanController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $mortgateLoanData = new MortgateLoanData();

        return $this->render('views/app/simulator/mortgage_loan/index.html.twig', [
            'mortgateLoanData' => $mortgateLoanData,
        ]);
    }
}