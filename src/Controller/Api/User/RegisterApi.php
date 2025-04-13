<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Entity\User\User;
use App\Form\User\RegisterFormType;
use App\Security\SecurityAuthenticator;
use App\Services\Manager\User\UserManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route(path: '/api')]
class RegisterApi extends AbstractController
{
    public function __construct(
        private readonly UserManager $userManager
    ) {
    }

    #[Route(path: '/register', methods: ['POST'], name: 'api_register')]
    public function register(Request $request): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->createUser($user);

            return new JsonResponse([
                'success' => true,
            ], 201);
        }

        return new JsonResponse([
            'success' => false,
            'errors' => $this->getFormErrors($form),
        ], 400);
    }

    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = [];

                foreach ($child->getErrors(true) as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }
}
