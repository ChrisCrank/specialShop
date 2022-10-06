<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
class Language
{
    public function __construct(?int $id = null)
    {
        $this->id = $id;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('language')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('language')]
    private ?string $iso = null;

    #[ORM\Column(length: 255)]
    #[Groups('language')]
    private ?string $displayName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIso(): ?string
    {
        return $this->iso;
    }

    public function setIso(string $iso): self
    {
        $this->iso = $iso;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }
}
