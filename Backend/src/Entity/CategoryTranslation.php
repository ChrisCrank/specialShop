<?php

namespace App\Entity;

use App\Repository\CategoryTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryTranslationRepository::class)]
class CategoryTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('category_translation')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('category_translation')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('category_translation')]
    private ?string $description = null;

    #[ORM\ManyToOne(fetch: "LAZY", inversedBy: 'translation')]
    #[Groups('category_translation_category')]
    private ?Category $category = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('language')]
    private ?Language $language = null;

    #[ORM\Column]
    #[Groups('category_translation')]
    private ?int $categoryId = null;

    #[ORM\Column(name: 'language_id')]
    #[Groups('category_translation')]
    public ?int $languageId = null;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    /**
     * @param int|null $languageId
     */
    public function setLanguageId(?int $languageId): self
    {
        $this->languageId = $languageId;

        return $this;
    }
}
