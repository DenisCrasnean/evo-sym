<?php

namespace App\Security\Token;

use App\Entity\PasswordReset;
use App\Entity\User;
use App\Repository\PasswordResetRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Uid\Uuid;

class PasswordResetToken
{
    private string $tokenLifetimeInMinutes;

    private PasswordResetRepository $passwordResetRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(string $tokenLifetimeInMinutes, PasswordResetRepository $passwordResetRepository, EntityManagerInterface $entityManager)
    {
        $this->tokenLifetimeInMinutes = $tokenLifetimeInMinutes;
        $this->passwordResetRepository = $passwordResetRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function generate(User $user): void
    {
        if (false !== $this->isExpired($user->getPasswordResetToken())) {
            $passwordReset = new PasswordReset();
            $passwordReset
                ->setUser($user)
                ->setCreatedAt(new DateTimeImmutable('now'))
                ->setToken(Uuid::v4());

            $this->entityManager->persist($passwordReset);
            $this->entityManager->flush();
        }
    }

    /**
     * @throws Exception
     */
    public function isExpired(string $token): bool
    {
        $passwordReset = $this->passwordResetRepository
            ->findByToken($token);

        $dateInterval = date_diff(
            $passwordReset
                ->getCreatedAt()
                ->add(new DateInterval('PT'.$this->tokenLifetimeInMinutes.'M')),
             new DateTimeImmutable('now')
        );

        if (0 !== $dateInterval->i) {
            return false;
        }

        return true;
    }
}
