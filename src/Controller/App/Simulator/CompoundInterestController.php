<?php

declare(strict_types=1);

namespace Finaro\Controller\App\Simulator;

use Finaro\Entity\Simulator\CompoundInterestData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[
    Route('/simulation/interets-composes', name: 'finaro_app_simulator_compound_interest'),
    IsGranted('IS_AUTHENTICATED_FULLY')
]
class CompoundInterestController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $compoundInterestData = new CompoundInterestData();

        return $this->render('views/app/simulator/compound_interest/index.html.twig', [
            'compoundInterestData' => $compoundInterestData,
        ]);
    }
}
