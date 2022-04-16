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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Alias
{
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    #[Assert\NotBlank]
    protected ?string $name;

    #[ORM\Column(type: 'string', length: 2, nullable: false)]
    protected ?string $locale;

    #[ORM\ManyToOne(targetEntity: MappableEntity::class, inversedBy: 'aliases')]
    #[ORM\JoinColumn(nullable: false)]
    protected ?MappableEntity $mappedEntity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getMappedEntity(): ?MappableEntity
    {
        return $this->mappedEntity;
    }

    public function setMappedEntity(MappableEntity $mappedEntity): self
    {
        $this->mappedEntity = $mappedEntity;

        return $this;
    }

    public function __toString(): string
    {
        return "{$this->getName()}";
    }
}
