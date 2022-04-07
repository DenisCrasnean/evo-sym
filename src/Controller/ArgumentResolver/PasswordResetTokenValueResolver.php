<?php

namespace App\Controller\ArgumentResolver;

use App\Repository\PasswordResetRepository;
use App\Security\Token\PasswordResetToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class PasswordResetTokenValueResolver implements ArgumentValueResolverInterface
{
    private string $tokenLifetimeInMinutes;

    private PasswordResetRepository $passwordResetRepository;

    private EntityManagerInterface $entityManager;

    public function __construct($tokenLifetimeInMinutes, EntityManagerInterface $entityManager, PasswordResetRepository $passwordResetRepository)
    {
        $this->tokenLifetimeInMinutes = $tokenLifetimeInMinutes;
        $this->passwordResetRepository = $passwordResetRepository;
        $this->entityManager = $entityManager;
    }


    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return PasswordResetToken::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        yield new PasswordResetToken($this->tokenLifetimeInMinutes, $this->passwordResetRepository, $this->entityManager);
    }
}
