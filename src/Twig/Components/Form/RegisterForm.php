<?php

declare(strict_types=1);

namespace Finaro\Twig\Components\Form;

use Finaro\Entity\User\User;
use Finaro\Form\User\RegisterFormType;
use Finaro\Services\Manager\User\UserManager;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;

#[AsLiveComponent('RegisterForm', template: 'components/Form/RegisterForm.html.twig')]
class RegisterForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?User $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            RegisterFormType::class,
            $this->initialFormData
        );
    }

    #[LiveAction]
    public function saveRegister(UserManager $userManager): RedirectResponse
    {
        $this->submitForm();

        $user = $this->getForm()->getData();

        if ($this->getForm()->isValid()) {

            $userManager->createUser($user);
            $this->addFlash('success', 'Votre compte a été créé avec succès.');

            return $this->redirectToRoute('finaro_login');
        }
    }
}
