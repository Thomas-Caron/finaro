<?php

declare(strict_types=1);

namespace App\Controller\Api\Date;

use App\Repository\Date\MonthRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class MonthApi extends AbstractController
{
    public function __construct(
        private readonly MonthRepository $monthRepository
    ) {
    }

    #[Route(path: '/months', methods: ['GET'], name: 'api_get_months')]
    public function index(): JsonResponse
    {
        $months = $this->monthRepository->findAll();

        $data = array_map(fn($month) => [
            'label' => $month->getName(),
            'value' => $month->getSlug()
        ], $months);

        return $this->json([
            'success' => true,
            'data' => $data
        ], 200);
    }
}
