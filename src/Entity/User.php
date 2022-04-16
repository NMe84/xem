<?php

/*
 * The Xross Entity Map
 * https://github.com/NMe84/xem
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use App\Security\Role;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Index(columns: ['Name'])]
#[ORM\Index(columns: ['Email'])]
class User implements UserInterface
{
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    #[Assert\NotBlank]
    protected ?string $name;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    protected ?string $password;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    protected ?string $activationCode;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Assert\Email]
    #[Assert\NotBlank]
    protected ?string $email;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    protected string $role;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => false])]
    protected bool $active;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => false])]
    protected bool $deleted;

    #[ORM\Column(type: 'datetime', nullable: false)]
    protected ?DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $updatedAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $latestActivity;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => false])]
    protected bool $preferenceEmailNewAccounts;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => false])]
    protected bool $preferenceEmailNewShows;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => false])]
    protected bool $preferenceEmailPublicRequests;

    public function __construct()
    {
        $this->role = Role::USER;
        $this->active = false;
        $this->deleted = false;
        $this->createdAt = new DateTime();
        $this->preferenceEmailNewAccounts = false;
        $this->preferenceEmailNewShows = false;
        $this->preferenceEmailPublicRequests = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getActivationCode(): ?string
    {
        return $this->activationCode;
    }

    public function setActivationCode(?string $activationCode): self
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLatestActivity(): ?DateTimeInterface
    {
        return $this->latestActivity;
    }

    public function setLatestActivity(?DateTimeInterface $latestActivity): self
    {
        $this->latestActivity = $latestActivity;

        return $this;
    }

    public function isPreferenceEmailNewAccounts(): bool
    {
        return $this->preferenceEmailNewAccounts;
    }

    public function setPreferenceEmailNewAccounts(bool $preferenceEmailNewAccounts): self
    {
        $this->preferenceEmailNewAccounts = $preferenceEmailNewAccounts;

        return $this;
    }

    public function isPreferenceEmailNewShows(): bool
    {
        return $this->preferenceEmailNewShows;
    }

    public function setPreferenceEmailNewShows(bool $preferenceEmailNewShows): self
    {
        $this->preferenceEmailNewShows = $preferenceEmailNewShows;

        return $this;
    }

    public function isPreferenceEmailPublicRequests(): bool
    {
        return $this->preferenceEmailPublicRequests;
    }

    public function setPreferenceEmailPublicRequests(bool $preferenceEmailPublicRequests): self
    {
        $this->preferenceEmailPublicRequests = $preferenceEmailPublicRequests;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->getName();
    }

    public function getRoles(): array
    {
        return [$this->getRole()];
    }

    public function eraseCredentials()
    {
        // Intentionally left blank.
    }

    public function __toString(): string
    {
        return $this->getName();
    }

}
