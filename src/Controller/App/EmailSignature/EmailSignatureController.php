<?php

declare(strict_types=1);

namespace App\Controller\App\EmailSignature;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class EmailSignatureController extends AbstractController
{
    #[Route('/signature-mail', name: 'app_email_signature')]
    public function __invoke(): Response
    {
        return $this->render('app/emailSignature/index.html.twig');
    }
}
