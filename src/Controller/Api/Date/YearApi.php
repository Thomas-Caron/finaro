<?php

declare(strict_types=1);

namespace App\Controller\Api\Date;

use App\Repository\Date\YearRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api')]
class YearApi extends AbstractController
{
    public function __construct(
        private readonly YearRepository $yearRepository
    ) {
    }

    #[Route(path: '/years', methods: ['GET'], name: 'api_get_years')]
    public function index(): JsonResponse
    {
        $years = $this->yearRepository->findAll();

        $data = array_map(fn($year) => [
            'label' => $year->getValue(),
            'value' => $year->getValue()
        ], $years);

        return $this->json([
            'success' => true,
            'data' => $data
        ], 200);
    }
}
