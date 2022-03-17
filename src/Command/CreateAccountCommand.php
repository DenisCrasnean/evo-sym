<?php

declare(strict_types=1);

namespace App\Command;

use App\Controller\Dto\UserDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAccountCommand extends Command
{
    protected static $defaultName = "app:account:create";

    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    private UserDto $userDto;

    private string $plainPassword;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        UserDto $userDto
    )
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userDto = $userDto;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('firstName', InputArgument::REQUIRED, 'Enter your first name');
        $this->addArgument('lastName', InputArgument::REQUIRED, 'Enter your last name');
        $this->addArgument('email', InputArgument::REQUIRED, 'Enter your email');
        $this->addArgument('cnp', InputArgument::REQUIRED, 'Enter your CNP');
        $this->addOption(
            'role',
            null,
            InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'User\'s role',
            ['ROLE_ADMIN']
        );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Enter a stong password');
        $question->setHidden(true);
        $this->plainPassword = $helper->ask($input, $output, $question);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);

        $user = User::createFromDto($this->userDto);
        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorArray = [];

            foreach ($errors as $error) {
                $errorArray = [
                    $error->getPropertyPath() => $error->getMessage(),
                ];
            }

            $inputOutput->error($errorArray);

            return self::FAILURE;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);
        $savedUserDto = $this->userDto->createFromUser($user);

        $successMessage = [
            'User account with the role of successfully created!',
            $savedUserDto
        ];

        $inputOutput->success($successMessage);

        return self::SUCCESS;
    }
}