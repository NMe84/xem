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

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Show extends MappableEntity
{
    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    #[Assert\NotBlank]
    protected ?string $name;

    /** @var Collection|Season[] */
    #[ORM\OneToMany(targetEntity: Season::class, mappedBy: 'show')]
    #[ORM\OrderBy(['number' => 'ASC'])]
    protected Collection|array $seasons;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /** @return Collection|Season[] */
    public function getSeasons(): Collection|array
    {
        return $this->seasons;
    }

    public function __toString(): string
    {
        return "{$this->getName()}";
    }
}
