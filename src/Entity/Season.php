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

#[ORM\Entity]
class Season extends MappableEntity
{
    #[ORM\Column(type: 'integer', nullable: false)]
    protected ?int $number;

    #[ORM\ManyToOne(targetEntity: Show::class, inversedBy: 'seasons')]
    #[ORM\JoinColumn(nullable: false)]
    protected ?Show $show;

    /** @var Collection|Episode[] */
    #[ORM\OneToMany(targetEntity: Episode::class, mappedBy: 'season')]
    #[ORM\OrderBy(['number' => 'ASC'])]
    protected Collection|array $episodes;

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getShow(): ?Show
    {
        return $this->show;
    }

    public function setShow(Show $show): self
    {
        $this->show = $show;

        return $this;
    }

    /** @return Collection|Episode[] */
    public function getEpisodes(): Collection|array
    {
        return $this->episodes;
    }

    public function getPaddedNumber(int $length = 2): string
    {
        return str_pad((string) $this->getNumber(), $length, '0', STR_PAD_LEFT);
    }

    public function __toString(): string
    {
        return "{$this->getShow()?->getName()}: S{$this->getPaddedNumber()}";
    }
}
