<?php

namespace App\Entity;

use App\Repository\ProductTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductTranslationRepository::class)]
class ProductTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('product_translation')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('product_translation')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('product_translation')]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups('product_translation')]
    private ?int $languageId = null;

    #[ORM\ManyToOne(fetch: "LAZY",inversedBy: 'translation')]
    #[Groups('product_translation_product')]
    private ?Product $product = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('language')]
    private ?Language $language = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('product_translation')]
    private ?string $shortDescription = null;


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

    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    public function setLanguageId(int $languageId): self
    {
        $this->languageId = $languageId;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
