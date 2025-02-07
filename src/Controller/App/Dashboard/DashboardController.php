<?php

declare(strict_types=1);

namespace Finaro\Controller\App\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard', name: 'finaro_app_dashboard')]
class DashboardController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('views/app/dashboard/index.html.twig');
    }
}
