<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\StorageService;
use Exception;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:init-storage',
    description: 'Initialize storage from request.json'
)]
class InitializeStorageCommand extends Command
{
    public function __construct(
        private readonly StorageService $storageService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $jsonPath = 'request.json';
            if (!file_exists($jsonPath)) {
                throw new RuntimeException('request.json not found');
            }

            $jsonContent = file_get_contents($jsonPath);

            if ($jsonContent === false) {
                throw new RuntimeException('Failed to read the content of request.json');
            }

            $this->storageService->initializeFromJson($jsonContent);

            $output->writeln('Storage initialized successfully');
            return Command::SUCCESS;
        } catch (Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
