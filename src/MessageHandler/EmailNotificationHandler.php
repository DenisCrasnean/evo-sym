<?php

namespace App\MessageHandler;

use App\Message\EmailNotification;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailNotificationHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;

    private UserRepository $userRepository;

    public function __construct(MailerInterface $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(EmailNotification $emailNotification)
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $email = (new Email())
                ->from(new Address('support@gym.con', 'Most wonderfull gym!'))
                ->to(new Address($user->getEmail(), $user->getFirstName().' '.$user->getLastname()))
                ->replyTo(new Address('support@gym.con', 'Most wonderfull gym!'))
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Gym | Random mail with messenger component!')
                ->text($emailNotification->getContent());

            $this->mailer->send($email);
        }
    }
}
