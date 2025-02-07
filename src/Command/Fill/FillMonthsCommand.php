<?php

declare(strict_types=1);

namespace Finaro\Command\Fill;

use Doctrine\ORM\EntityManagerInterface;
use Finaro\Entity\Date\Month;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'finaro:fill-months',
    description: 'Fill Month table with months of the year.',
)]
class FillMonthsCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $months = [
            ['name' => 'Janvier', 'index' => 1],
            ['name' => 'Février', 'index' => 2],
            ['name' => 'Mars', 'index' => 3],
            ['name' => 'Avril', 'index' => 4],
            ['name' => 'Mai', 'index' => 5],
            ['name' => 'Juin', 'index' => 6],
            ['name' => 'Juillet', 'index' => 7],
            ['name' => 'Août', 'index' => 8],
            ['name' => 'Septembre', 'index' => 9],
            ['name' => 'Octobre', 'index' => 10],
            ['name' => 'Novembre', 'index' => 11],
            ['name' => 'Décembre', 'index' => 12],
        ];

        $repository = $this->entityManager->getRepository(Month::class);

        foreach ($months as $monthData) {
            // Check if a month with the same name or index already exists
            $existingMonth = $repository->findOneBy(['name' => $monthData['name']]);

            if ($existingMonth) {
                $output->writeln(sprintf('The month "%s" already exists, skipping.', $monthData['name']));
                continue;
            }

            // Create a new month if it doesn't exist
            $month = new Month();
            $month->setName($monthData['name']);
            $month->setIndex($monthData['index']);
 
            $this->entityManager->persist($month);
            $output->writeln(sprintf('The month "%s" has been added.', $monthData['name']));
        }

        $this->entityManager->flush();

        $output->writeln('Operation completed successfully.');

        return Command::SUCCESS;
    }
}