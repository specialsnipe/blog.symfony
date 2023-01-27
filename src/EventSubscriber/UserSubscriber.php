<?php

namespace App\EventSubscriber;

use Symfony\Component\Mime\Address;
use App\Event\RegisteredUserEvent;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var EmailVerifier
     */
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            RegisteredUserEvent::NAME => 'onUserRegister',
        ];
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onUserRegister(RegisteredUserEvent $registeredUserEvent)
    {
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $registeredUserEvent->getRegisteredUser(),
            (new TemplatedEmail())
                ->from(new Address('specialsnipe1@mail.ru', 'Mail Bot'))
                ->to($registeredUserEvent->getRegisteredUser()->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }
}
