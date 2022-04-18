<?php

namespace App\Controller;

use App\Controller\Dto\DtoInterface;
use App\Controller\Dto\UserDto;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(path="/api/user", methods={"POST"})
 **/
class UserController
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @Route(path="/store", methods={"POST"}, name="app_user_store")
     */
    public function store(Request $request, UserDto $userDto, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $userDto->fromArray($request->toArray());

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $user->getPlainPassword(),
        );

        $user->setPassword($hashedPassword);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorArray = [];

            foreach ($errors as $error) {
                $errorArray = [
                    $error->getPropertyPath() => $error->getMessage(),
                ];
            }

            return new JsonResponse($errorArray);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);
        $savedUserDto = $userDto->fromObject($user);
        $this->logger->info('User created successfully!', ['email' => $savedUserDto->getEmail()]);

        return new JsonResponse($savedUserDto, Response::HTTP_CREATED);
    }
}
