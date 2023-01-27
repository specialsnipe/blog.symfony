<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

     public function loadUserByIdentifier(string $identifier): UserInterface
     {
         $user = $this->userRepository->loadUserByUsername($identifier);
         if (!$user) {
             throw new \Exception('TODO: fill in loadUserByIdentifier() inside '.__FILE__);
         }

         return $user;
     }

     /**
      * Refreshes the user after being reloaded from the session.
      *
      * When a user is logged in, at the beginning of each request, the
      * User object is loaded from the session and then this method is
      * called. Your job is to make sure the user's data is still fresh by,
      * for example, re-querying for fresh User data.
      *
      * If your firewall is "stateless: true" (for a pure API), this
      * method is not called.
      *
      * @return UserInterface
      */
     public function refreshUser(UserInterface $user)
     {
         if (!$user instanceof User) {
             throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
         }

         return $user;
//         throw new \Exception('TODO: fill in refreshUser() inside '.__FILE__);
     }

     /**
      * Tells Symfony to use this provider for this User class.
      */
     public function supportsClass(string $class)
     {
         return User::class === $class || is_subclass_of($class, User::class);
     }

     /**
      * Upgrades the hashed password of a user, typically for using a better hash algorithm.
      */
     public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
     {
         // TODO: when hashed passwords are in use, this method should:
         // 1. persist the new password in the user storage
         // 2. update the $user object with $user->setPassword($newHashedPassword);
     }
}
