<?php

declare(strict_types=1);

namespace Finaro\Controller\App\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CommonAccountController extends AbstractController
{
    #[Route(
        '/compte/commun/{year?}/{month?}',
        name: 'finaro_app_account_common',
        requirements: ['year' => '\d{4}', 'month' => '\d{1,2}'],
    )]
    public function index(?int $year, ?int $month): Response
    {
        $currentYear = (int) (new \DateTime())->format('Y');
        $currentMonth = (int) (new \DateTime())->format('m');

        if ($year === null || $month === null) {
            $redirectYear = $year ?? $currentYear;
            $redirectMonth = $month ?? $currentMonth;

            return $this->redirectToRoute('finaro_app_account_common', [
                'year' => $redirectYear,
                'month' => $redirectMonth,
            ]);
        }
        
        $year = $year ?? $currentYear;
        $month = $month ?? $currentMonth;

        return $this->render('views/app/account/common/index.html.twig', [
            'controller_name' => 'CommonAccountController',
        ]);
    }
}
