<?php

declare(strict_types=1);

namespace App\Controller\App\Simulator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CompoundInterestController extends AbstractController
{
    #[Route('/simulation/interets-composes', name: 'app_simulator_compound_interest')]
    public function __invoke(): Response
    {
        return $this->render('app/simulator/compoundInterest/index.html.twig');
    }
}
