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

use App\Security\Role;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(indexes={@ORM\Index(columns={"Name"}), @ORM\Index(columns={"Email"})})
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\NotBlank
     */
    protected ?string $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected ?string $password;

    /**
     * Helper variable used by the authenticator. Obviously does not get stored in the database.
     */
    protected ?string $plainTextPassword;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    protected ?string $activationCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Email
     * @Assert\NotBlank
     */
    protected ?string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    protected string $role;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    protected ?bool $active;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    protected bool $deleted;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected ?DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTimeInterface $latestActivity;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    protected bool $preferenceEmailNewAccounts;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
    protected bool $preferenceEmailNewShows;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default":false})
     */
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

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getPlainTextPassword(): ?string
    {
        return $this->plainTextPassword;
    }

    public function setPlainTextPassword(?string $plainTextPassword): void
    {
        $this->plainTextPassword = $plainTextPassword;
    }

    public function getActivationCode(): ?string
    {
        return $this->activationCode;
    }

    public function setActivationCode(?string $activationCode): void
    {
        $this->activationCode = $activationCode;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getLatestActivity(): ?DateTimeInterface
    {
        return $this->latestActivity;
    }

    public function setLatestActivity(?DateTimeInterface $latestActivity): void
    {
        $this->latestActivity = $latestActivity;
    }

    public function isPreferenceEmailNewAccounts(): bool
    {
        return $this->preferenceEmailNewAccounts;
    }

    public function setPreferenceEmailNewAccounts(bool $preferenceEmailNewAccounts): void
    {
        $this->preferenceEmailNewAccounts = $preferenceEmailNewAccounts;
    }

    public function isPreferenceEmailNewShows(): bool
    {
        return $this->preferenceEmailNewShows;
    }

    public function setPreferenceEmailNewShows(bool $preferenceEmailNewShows): void
    {
        $this->preferenceEmailNewShows = $preferenceEmailNewShows;
    }

    public function isPreferenceEmailPublicRequests(): bool
    {
        return $this->preferenceEmailPublicRequests;
    }

    public function setPreferenceEmailPublicRequests(bool $preferenceEmailPublicRequests): void
    {
        $this->preferenceEmailPublicRequests = $preferenceEmailPublicRequests;
    }

    public function getRoles()
    {
        return [$this->getRole()];
    }

    public function getSalt()
    {
        // The encryption method uses a salt natively, no need to implement this.
        return null;
    }

    public function getUsername()
    {
        return $this->getName();
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
