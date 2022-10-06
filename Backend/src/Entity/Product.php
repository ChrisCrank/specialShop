<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('product')]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('product')]
    private ?int $stock = null;

    #[ORM\Column]
    #[Groups('product')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups('product')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductTranslation::class, cascade: ['all'])]
    #[Groups('product_translation')]
    private Collection $translation;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[Groups('product')]
    private Collection $categories;

    #[Groups('categoryIds')]
    private array $categoryIds;

    #[ORM\Column]
    #[Groups('product')]
    private ?float $price = null;

    #[Groups('product')]
    private ?string $name = "";

    #[Groups('product')]
    private ?string $description = "";

    #[Groups('product')]
    private ?string $shortDescription = "";

    #[Groups('product')]
    private array $calculatedImages = [];

    #[Groups('product')]
    #[ORM\Column]
    private ?bool $active = null;

    #[Groups('product')]
    #[ORM\Column]
    private ?int $sort = null;

    #[Groups('product')]
    #[ORM\Column(length: 255)]
    private ?string $img = null;

    #[Groups('product')]
    #[ORM\Column(length: 255)]
    private ?string $productNumber = null;

    public function __construct()
    {
        $this->translation = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

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

    /**
     * @return Collection<int, ProductTranslation>
     */
    public function getTranslation(): Collection
    {
        return $this->translation;
    }

    public function addTranslation(ProductTranslation $translation): self
    {
        if (!$this->translation->contains($translation)) {
            $this->translation->add($translation);
            $translation->setProduct($this);
        }

        return $this;
    }

    public function removeTranslation(ProductTranslation $translation): self
    {
        if ($this->translation->removeElement($translation)) {
            // set the owning side to null (unless already changed)
            if ($translation->getProduct() === $this) {
                $translation->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @param string|null $description
     */
    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
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

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getProductNumber(): ?string
    {
        return $this->productNumber;
    }

    public function setProductNumber(string $productNumber): self
    {
        $this->productNumber = $productNumber;

        return $this;
    }

    public function getCategoryIds() :array{
        /**
         * TODO: fine a way only product_category table is queried so the whole category entity dont have to be loaded
         */
        $ids = [];
        foreach($this->categories as $category){
            $ids[] = $category->getId();
        }
        return $ids;
    }
}
