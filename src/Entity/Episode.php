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

#[ORM\Entity]
class Episode extends MappableEntity
{
    #[ORM\Column(type: 'integer', nullable: false)]
    protected ?int $number;

    #[ORM\ManyToOne(targetEntity: Season::class, inversedBy: 'seasons')]
    #[ORM\JoinColumn(nullable: false)]
    protected ?Season $season;

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getPaddedNumber(int $length = 2): string
    {
        return str_pad((string) $this->getNumber(), $length, '0', STR_PAD_LEFT);
    }

    public function __toString(): string
    {
        return "{$this->getSeason()?->getShow()?->getName()}: S{$this->getSeason()?->getPaddedNumber()}E{$this->getPaddedNumber()}";
    }
}
