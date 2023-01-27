<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
#[UniqueEntity(fields: 'email', message: 'У вас уже есть аккаунт')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const GITHUB_OAUTH = 'Github';
    public const GOOGLE_OAUTH = 'Google';

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public function __construct(
        int $clientId,
        string $email,
        string $username,
        string $oauthType,
        array $roles
    ) {
        $this->clientId = $clientId;
        $this->email = $email;
        $this->username = $username;
        $this->oauthType = $oauthType;
        $this->lastLogin = new \DateTimeImmutable();
        $this->roles = $roles;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private int $clientId;

    #[ORM\Column(type: 'string', unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $username;

    #[ORM\Column(type: 'string')]
    private string $oauthType;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $lastLogin;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Length(min: 6, minMessage: 'Пароль должен быть не менее 6 символов')]
    private ?string $password;

    #[Assert\NotBlank]
    private string|null $plainPassword;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string', nullable: true)]
    private string $confirmationCode;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public static function fromGithubRequest(
        int $clientId,
        string $email,
        string $username
    ): User {
        return new self(
            $clientId,
            $email,
            $username,
            self::GITHUB_OAUTH,
            [self::ROLE_USER]
        );
    }

    public static function fromGoogleRequest(
        string $clientId,
        string $email,
        string $username
    ): User {
        return new self(
            $clientId,
            $email,
            $username,
            self::GOOGLE_OAUTH,
            [self::ROLE_USER]
        );
    }

    /**
     * @param int $clientId
     */
    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }


    public function getConfirmationCode(): string
    {
        return $this->confirmationCode;
    }

    public function setConfirmationCode(string $confirmationCode): self
    {
        $this->confirmationCode = $confirmationCode;

        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnable(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getRoles(): array
    {
        return [
            'ROLE_USER',
        ];
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return 'sad';
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
