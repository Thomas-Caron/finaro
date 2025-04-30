<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Entity\User\User;
use App\Form\User\RegisterFormType;
use App\Services\Helper\FormHelper;
use App\Services\Manager\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class RegisterApi extends AbstractController
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly FormHelper $formHelper
    ) {
    }

    #[Route(path: '/user/register', methods: ['POST'], name: 'api_user_register')]
    public function index(Request $request): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userManager->createUser($user);

            return $this->json([
                'success' => true,
            ], 201);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }
}
