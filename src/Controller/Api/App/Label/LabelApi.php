<?php

declare(strict_types=1);

namespace App\Controller\Api\App\Label;

use App\DataEntity\App\Label\LabelCollectionData;
use App\Entity\Label\Label;
use App\Form\App\Account\Movement\LabelCollectionFormType;
use App\Form\App\Label\LabelFormType;
use App\Repository\Label\LabelRepository;
use App\Services\Helper\FormHelper;
use App\Services\Manager\Label\LabelManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class LabelApi extends AbstractController
{
    public function __construct(
        private readonly FormHelper $formHelper,
        private readonly LabelRepository $labelRepository,
        private readonly LabelManager $labelManager
    ) {
    }

    #[Route(path: '/label', methods: ['GET'], name: 'api_label_get')]
    public function index(Request $request): JsonResponse
    {
        $labels = $this->labelRepository->findBy(
            ['createdBy' => $this->getUser()],
            ['name' => 'ASC']
        );

        $data = array_map(fn($label) => [
            'id' => $label->getId(),
            'name' => $label->getName(),
            'slug' => $label->getSlug(),
            'color' => $label->getColor(),
            'updatedAt' => $label->getUpdatedAt(),
            'createdAt' => $label->getCreatedAt(),
        ], $labels);
        
        return $this->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    #[Route(path: '/label', methods: ['POST'], name: 'api_label_add')]
    public function create(Request $request): JsonResponse
    {
        $labelCollection = new LabelCollectionData();
        $form = $this->createForm(LabelCollectionFormType::class, $labelCollection);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->labelManager->addLabels($labelCollection, $this->getUser());
            
            return $this->json([
                'success' => true,
            ], 201);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }

    #[Route(path: '/label/update/{id}', methods: ['PUT'], name: 'api_label_update')]
    public function update(Request $request, Label $label): JsonResponse
    {
        $labelModified = new Label();
        $form = $this->createForm(LabelFormType::class, $labelModified);
        $form->submit($request->getPayload()->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->labelManager->update($label, $labelModified);
            
            return $this->json([
                'success' => true,
            ], 204);
        }

        return $this->json([
            'success' => false,
            'errors' => $this->formHelper->getErrors($form),
        ], 400);
    }

    #[Route(path: '/label/remove/{id}', methods: ['DELETE'], name: 'api_label_remove')]
    public function remove(Label $label): JsonResponse
    {
        $this->labelManager->remove($label);

        return $this->json([
            'success' => true,
        ], 204);
    }
}
