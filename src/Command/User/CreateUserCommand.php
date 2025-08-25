<?php

namespace App\Command\User;

use App\Entity\User\User;
use App\Services\Manager\User\UserManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:user:create',
    description: 'Create a user.'
)]
class CreateUserCommand extends Command
{
    public function __construct(private readonly UserManager $userManager)
    {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $user = new User();

        $mailQuestion = new Question('Adresse email ?');
        $user->setEmail(
            $helper->ask($input, $output, $mailQuestion)
        );

        $passwordQuestion = new Question('Password ?');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);
        $user->setPassword(
            $helper->ask($input, $output, $passwordQuestion)
        );

        $firsntameQuestion = new Question('Prénom ?');
        $user->setFirstname(
            $helper->ask($input, $output, $firsntameQuestion)
        );

        $lastnameQuestion = new Question('Nom ?');
        $user->setLastname(
            $helper->ask($input, $output, $lastnameQuestion)
        );

        $this->userManager->createUser($user);

        $io->success("Vous pouvez maintenant vous connecter avec l'adresse email : " . $user->getEmail());

        return Command::SUCCESS;
    }
}
