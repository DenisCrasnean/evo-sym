<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordResetRequestType;
use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use App\Security\Token\PasswordResetToken;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Exception;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AuthController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private UserRepository $userRepository;

    private MailerInterface $mailer;

    private LoggerInterface $logger;

    private RouterInterface $router;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        MailerInterface $mailer,
        LoggerInterface $userLogger,
        RouterInterface $router
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->logger = $userLogger;
        $this->router = $router;
    }

    /**
     * @Route(path="/account/reset-password/{token}", name="account_password_reset")
     **/
    public function resetPasswordAction(string $token, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        try {
            $user = $this->userRepository
                ->findByPasswordResetToken($token);

        } catch (NoResultException|NonUniqueResultException $e) {
            $this->logger
                ->error("User hasn't been found into the database by the repository method findByResetPasswordToken()!", [
                    'exception' => $e,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                    'token' => $token,
                    'datetime' => new DateTime('now'),
                ]);

            return $this->render('account/password-reset-form.html.twig', [
                'errors' => $e->getMessage(),
            ]);
        }

        $form = $this->createForm(PasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->logger
                ->info('User password reset completed successfully!', [
                    'datetime' => new DateTime('now'),
                    'userIdentifier' => $user->getUserIdentifier(),
            ]);

            return $this->redirectToRoute('dashboard');
        }

        return $this->renderForm('account/password-reset-form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route(path="/account/reset-password")
     */
    public function resetPasswordRequestAction(Request $request, PasswordResetToken $passwordResetToken): Response
    {
        $form = $this->createForm(PasswordResetRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('e-mail')->getData();

            try {
                $user = $this->userRepository
                    ->findByEmail($email);

                dd($user);
                $passwordResetToken->generate($user);
                $this->sendResetPasswordMail($user);
                $this->logger
                    ->info("User password reset request completed successfully and the password reset link was sent to user's e-mail.", [
                        'userIdentifier' => $user->getUserIdentifier(),
                        'datetime' => new DateTime('now'),
                    ]);
            } catch (NoResultException|NonUniqueResultException|TransportExceptionInterface|Exception $e) {
                $this->logger
                    ->error('User password reset request failed to complete!', [
                        'exception' => $e,
                        'message' => $e->getMessage(),
                        'datetime' => new DateTime('now'),
                ]);

                return $this->renderForm('account/password-reset-request-form.html.twig', [
                    'form' => $form,
                    'error' => $e->getMessage(),
                ]);
            }

            return $this->render('account/password-reset-completed.html.twig', [
                'email' => $user->getEmail(),
            ]);
        }

        return $this->renderForm('account/password-reset-form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendResetPasswordMail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('deniscrasnean@gmail.com', 'Gym app'))
            ->to(new Address($user->getEmail(), $user->getFirstName()))
            ->replyTo(new Address('deniscrasnean@gmail.com', 'Gym app'))
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Gym | Password Reset Link')
            ->htmlTemplate('email/password-reset-email.html.twig')
            ->context([
                'resetLink' => $this->router->generate('account_password_reset', ['token' => $user->getPasswordResetToken()], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

        $this->mailer->send($email);
    }

    /**
     * @Route(path="/account/dashboard", name="dashboard")
     **/
    public function dashboardAction(): Response
    {
        return $this->render('customers/dashboard.html.twig');
    }
}
