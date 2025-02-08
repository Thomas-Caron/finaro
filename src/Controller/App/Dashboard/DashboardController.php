<?php

declare(strict_types=1);

namespace Finaro\Controller\App\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[
    Route('/dashboard', name: 'finaro_app_dashboard'),
    IsGranted('IS_AUTHENTICATED_FULLY')
]
class DashboardController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('views/app/dashboard/index.html.twig');
    }
}
