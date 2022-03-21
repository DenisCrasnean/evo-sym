<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProgrammeImportFromCSVCommand extends Command
{
    private int $programmeMinTimeInMinutes;

    private int $programmeMaxTimeInMinutes;

    protected static $defaultName = "app:programme:import-csv";

    public function __construct(string $programmeMinTimeInMinutes, string $programmeMaxTimeInMinutes)
    {
        $this->programmeMinTimeInMinutes = (int) $programmeMinTimeInMinutes;
        $this->programmeMaxTimeInMinutes = (int) $programmeMaxTimeInMinutes;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        echo $this->programmeMinTimeInMinutes;
        echo $this->programmeMaxTimeInMinutes;

        return Command::SUCCESS;
    }
}