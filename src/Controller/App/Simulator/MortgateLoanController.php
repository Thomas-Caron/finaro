<?php

declare(strict_types=1);

namespace App\Controller\App\Simulator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class MortgateLoanController extends AbstractController
{
    #[Route('/simulation/credit-immobilier', name: 'app_simulator_mortgate_loan')]
    public function __invoke(): Response
    {
        return $this->render('app/simulator/mortgateLoan/index.html.twig');
    }
}
