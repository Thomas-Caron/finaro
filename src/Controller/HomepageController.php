<?php

declare(strict_types=1);

namespace Finaro\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'finaro_homepage')]
class HomepageController extends AbstractController
{
    
    public function __invoke(Request $request): Response
    {
        return $this->render('views/homepage/index.html.twig');
    }
}