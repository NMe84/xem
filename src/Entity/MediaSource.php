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
class MediaSource
{
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    #[Assert\NotBlank]
    protected ?string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    protected ?string $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    protected ?string $url;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    protected ?string $deeplinkTemplate;

    #[ORM\ManyToOne(targetEntity: MediaType::class, inversedBy: 'sources')]
    protected ?MediaType $type;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => true])]
    protected bool $unique;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => false])]
    protected bool $deleted;

    public function __construct()
    {
        $this->unique = true;
        $this->deleted = false;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDeeplinkTemplate(): ?string
    {
        return $this->deeplinkTemplate;
    }

    public function setDeeplinkTemplate(?string $deeplinkTemplate): self
    {
        $this->deeplinkTemplate = $deeplinkTemplate;

        return $this;
    }

    public function getType(): ?MediaType
    {
        return $this->type;
    }

    public function setType(?MediaType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isUnique(): bool
    {
        return $this->unique;
    }

    public function setUnique(bool $unique): self
    {
        $this->unique = $unique;

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

    public function __toString(): string
    {
        return $this->getName();
    }

}
