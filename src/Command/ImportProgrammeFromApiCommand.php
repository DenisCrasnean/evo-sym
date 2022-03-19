<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\Api\ProgrammeApiClient;
use App\Entity\Programme;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportProgrammeFromApiCommand extends Command
{
    protected static $defaultName = 'app:programme:import-from-api';

    private ProgrammeApiClient $programmeApiClient;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ProgrammeApiClient $programmeApiClient,
        EntityManagerInterface $entityManager
    ) {
        $this->programmeApiClient = $programmeApiClient;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        $response = $this->programmeApiClient->fetch('GET', 'sport-programs');
        $responseBody = $response->toArray();

        $decryptResponse = new CaesarDecryption();

        //  TODO
        //  Implement a service to map objet to response data
        foreach ($responseBody['data'] as $data) {
            $name = $decryptResponse->decipher($data['name'], 8);
            $description = $decryptResponse->decipher($data['description'], 8);

            $programme = new Programme();
            $programme->name = $name;
            $programme->description = $description;
            $programme->setStartTime(new \DateTime($data['startDate']));
            $programme->setEndTime(new \DateTime($data['endDate']));
            $programme->setIsOnline($data['isOnline']);

            try {
                $this->entityManager->persist($programme);
                $this->entityManager->flush();
            } catch (Exception $e) {
                $e->getMessage();
            }
        }

        $inputOutput->success('Programmes imported successfully!');

        return self::SUCCESS;
    }
}
