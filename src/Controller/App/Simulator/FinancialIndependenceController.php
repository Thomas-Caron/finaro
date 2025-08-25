<?php

declare(strict_types=1);

namespace App\Controller\App\Simulator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class FinancialIndependenceController extends AbstractController
{
    #[Route('/simulation/independance-financiere', name: 'app_simulator_financial_independence')]
    public function __invoke(): Response
    {
        return $this->render('app/simulator/financialIndependence/index.html.twig');
    }
}
