<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Twig\Environment;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class Mailer
{
    public const FROM_ADDRESS = 'kafkiansky@php.zone';

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    private EmailVerifier $emailVerifier;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(
        EmailVerifier $emailVerifier,
        \Swift_Mailer $mailer,
        Environment $twig
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendConfirmationMessage(User $user)
    {
//        $messageBody = $this->twig->render('registration/confirmation_email.html.twig', [
//            'user' => $user,
//        ]);
//
//        $message = new \Swift_Message();
//        $message
//            ->setSubject('Вы успешно прошли регистрацию!')
//            ->setFrom(self::FROM_ADDRESS)
//            ->setTo($user->getEmail())
//            ->setBody($messageBody, 'text/html');
//
//        $this->mailer->send($message);
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('specialsnipe1@mail.ru', 'Mail Bot'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }
}
