<?php

declare(strict_types=1);

namespace App\Tests\App\Integration\Command;

use App\Command\InitializeStorageCommand;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class InitializeStorageCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;

    public function testExecuteSuccess(): void
    {
        file_put_contents(
            'request.json',
            json_encode(
                [
                    ['type' => 'fruit', 'name' => 'Apple', 'quantity' => 10, 'unit' => 'kg'],
                    ['type' => 'vegetable', 'name' => 'Carrot', 'quantity' => 5, 'unit' => 'g']
                ]
            )
        );

        $exitCode = $this->commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('Storage initialized successfully', $this->commandTester->getDisplay());

        unlink('request.json');
    }

    public function testExecuteFileNotFound(): void
    {
        if (file_exists('request.json')) {
            unlink('request.json');
        }

        $exitCode = $this->commandTester->execute([]);

        $this->assertEquals(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Error: request.json not found', $this->commandTester->getDisplay());
    }

    public function testExecuteWithInvalidJson(): void
    {
        file_put_contents('request.json', 'invalid json content');

        $exitCode = $this->commandTester->execute([]);

        $this->assertEquals(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Error: Invalid JSON format', $this->commandTester->getDisplay());

        unlink('request.json');
    }

    public function testExecuteWithStorageServiceError(): void
    {
        file_put_contents(
            'request.json',
            json_encode(
                [
                    ['type' => 'fruit', 'name' => '', 'quantity' => -5, 'unit' => 'invalid_unit']
                ]
            )
        );

        $exitCode = $this->commandTester->execute([]);

        $this->assertEquals(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Error:', $this->commandTester->getDisplay());

        unlink('request.json');
    }

    protected function setUp(): void
    {
        self::bootKernel();

        $storageService = self::getContainer()->get(StorageService::class);

        $command = new InitializeStorageCommand($storageService);
        $this->commandTester = new CommandTester($command);
    }
}
