<?php

declare(strict_types=1);

namespace App\Controller\App\Label;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class LabelController extends AbstractController
{
    #[Route('/categories', name: 'app_label')]
    public function __invoke(): Response
    {
        return $this->render('app/label/index.html.twig');
    }
}
