<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('category')]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('category')]
    private ?int $sort = null;

    #[ORM\Column]
    #[Groups('category')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups('category')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: CategoryTranslation::class, cascade: ['all'])]
    #[Groups('category_translation')]
    private Collection $translation;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'categories')]
    #[Groups('category_products')]
    private Collection $products;

    #[ORM\Column]
    #[Groups('category')]
    private ?bool $active = null;

    #[ORM\OneToOne(inversedBy: 'parent', targetEntity: self::class, cascade: ['persist'], fetch: "LAZY")]
    #[Groups('category_parent')]
    private ?self $parent = null;

    #[ORM\Column(nullable: true)]
    #[Groups('category')]
    private ?int $parentId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('category')]
    private ?string $path = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('category')]
    private ?string $img = null;

    #[Groups('category')]
    private array $pathArr = [];

    #[Groups('category')]
    private ?string $name = "";

    #[Groups('category')]
    private ?string $description = "";

    #[Groups('category')]
    private array $calculatedImages = [];

    public function __construct()
    {
        $this->translation = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, CategoryTranslation>
     */
    public function getTranslation(): Collection
    {
        return $this->translation;
    }

    public function addTranslation(CategoryTranslation $translation): self
    {
        if (!$this->translation->contains($translation)) {
            $this->translation->add($translation);
            $translation->setCategory($this);
        }

        return $this;
    }

    public function removeTranslation(CategoryTranslation $translation): self
    {
        if ($this->translation->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getCategory() === $this) {
                $translation->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeCategory($this);
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtAutomatically()
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function setUpdatedAtAutomatically()
    {
        $this->setUpdatedAt(new \DateTimeImmutable());
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPathArr(): array
    {
        return array_values(array_filter(explode('|', $this->path))) ?? [];
    }

    /**
     * @return array
     */
    public function getCalculatedImages(): array
    {
        return $this->calculatedImages;
    }

    /**
     * @param array $calculatedImages
     */
    public function setCalculatedImages(array $calculatedImages): self
    {
        $this->calculatedImages = $calculatedImages;

        return $this;
    }


}
