<?php

declare(strict_types=1);

namespace Finaro\Controller\User;

use Finaro\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Finaro\Services\Manager\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserManager $userManager
    ) {
    }

    #[Route('/inscription', name: 'finaro_register')]
    public function index(Request $request): Response
    {
        $user = new User();

        return $this->render('user/register.html.twig', [
            'user' => $user,
        ]);
    }
}
