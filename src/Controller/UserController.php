<?php

namespace App\Controller;

use App\Controller\Dto\DtoInterface;
use App\Controller\Dto\UserDto;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route(methods={"POST"})
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function store(Request $request, DtoInterface $userDto): Response
    {
        $user = $userDto->fromArray($request->toArray());

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
        $savedUserDto = UserDto::createFromUser($user);

        $this->logger->info('User created successfully!', ['email' => $savedUserDto->email]);

        return new JsonResponse($savedUserDto, Response::HTTP_CREATED);
    }
}
