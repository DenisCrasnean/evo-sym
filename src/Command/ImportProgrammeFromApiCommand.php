<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\Api\ApiClientInterface;
use App\Controller\Dto\DtoInterface;
use App\Encryption\EncryptionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImportProgrammeFromApiCommand extends Command
{
    protected static $defaultName = 'app:programme:import-from-api';

    private ApiClientInterface $programmeApiClient;

    private DtoInterface $programmeDto;

    private EncryptionInterface $caesarEncryption;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ApiClientInterface $programmeApiClient,
        DtoInterface $programmeDto,
        EncryptionInterface $caesarEncryption,
        EntityManagerInterface $entityManager
    ) {
        $this->programmeApiClient = $programmeApiClient;
        $this->programmeDto = $programmeDto;
        $this->caesarEncryption = $caesarEncryption;
        $this->entityManager = $entityManager;

        parent::__construct();
    }
  
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $response = $this->programmeApiClient->fetch('GET', 'sport-programs');
        $response = $response->toArray();
        $decryptedData = [];

        foreach ($response['data'] as $data) {
            $decryptedDataKeys = [];
            $decryptedDataValues = [];

            foreach ($data as $key => $value) {
                $decryptedDataKeys[] = $key;
                $decryptedDataValues[] = $this->caesarEncryption->decrypt((string) $value);
            }

            $decryptedData[] = array_combine($decryptedDataKeys, $decryptedDataValues);
        }

        $programmes = $this->programmeDto->fromArrayCollection($decryptedData);

        foreach ($programmes as $programme) {
            $this->entityManager->persist($programme);
            $this->entityManager->flush();
        }

        $inputOutput->success('Programmes imported successfully!');

        return self::SUCCESS;
    }
}
