<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Controller\Dto\UserDto;

// UserDto
// UserArgumentResolver
// CreateUserFromDto
// CreateDtoFromUser

/**
 * @Route(path="/api/user", methods={"POST"})
 **/
class UserController
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(methods={"POST"})
     **/
    public function store(UserDto $userDto) : Response
    {
        $user = User::createFromDto($userDto);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->entityManager->refresh($user);
        $savedUserDto = UserDto::createFromUser($user);

        return new JsonResponse($savedUserDto, Response::HTTP_CREATED);
    }
}