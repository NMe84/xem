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

#[ORM\MappedSuperclass]
abstract class MappableEntity
{
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id;

    /** @var Collection|Alias[] */
    #[ORM\OneToMany(targetEntity: Alias::class, mappedBy: 'mappedEntity')]
    protected Collection|array $aliases;

    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return Collection|Alias[] */
    public function getAliases(): Collection|array
    {
        return $this->aliases;
    }

    /** @param $aliases Collection|Alias[] */
    public function setAliases(Collection|array $aliases): self
    {
        $this->aliases = $aliases;

        return $this;
    }
}
