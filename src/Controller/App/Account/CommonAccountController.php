<?php

declare(strict_types=1);

namespace App\Controller\App\Account;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CommonAccountController extends AbstractController
{
    #[Route('/compte/commun', name: 'app_account_common')]
    public function __invoke(): Response
    {
        return $this->render('app/account/common/index.html.twig');
    }
}