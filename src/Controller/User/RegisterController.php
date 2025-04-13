<?php

declare(strict_types=1);

namespace App\Controller\User;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function __invoke(): Response
    {
        return $this->render('user/register.html.twig');
    }
}
