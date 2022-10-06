<?php
namespace App\Model\Database\Product;
use App\Entity\Category;
use App\Entity\ProductTranslation;
use App\Entity\Language;
use App\Model\Database\AbstractEntityCreate;
use App\Entity\Product;
use App\Repository\ProductRepository;


/**
 * @property ProductRepository repository
 */
class ProductCreate extends AbstractEntityCreate
{
    /**
     * @param int $sort
     * @param int $stock
     * @param int $price
     * @param array $translatedNames -> [<languageId> => $value, ...]
     * @param array $translatedDescription -> [<languageId> => $value, ...]
     * @param array $translationShortDescription -> [<languageId> => $value, ...]
     * @param string|null $img
     * @param array|null $categoryIds
     * @param bool $active
     * @return Product
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * NOTE the Keys of $translatedNames should always also exist in $translatedDescription
     */
    public function byParams(
        int $sort,
        int $stock,
        float $price,
        string $productNumber,
        array $translatedNames,
        array $translatedDescription,
        array $translationShortDescription,
        string $img,
        ?array $categoryIds = [],
        bool $active = false,
    ): Product{
        $product = (new Product())
            ->setSort($sort)
            ->setStock($stock)
            ->setPrice($price)
            ->setProductNumber($productNumber)
            ->setImg($img)
            ->setActive($active);
        foreach($categoryIds as $categoryId)
            $product->addCategory(
                $this->entityManager->getReference(Category::class, $categoryId)
            );

        // insert translation
        foreach($translatedNames as $languageId => $value ){
            $translation = (new ProductTranslation())
                ->setName($value)
                ->setDescription($translatedDescription[$languageId])
                ->setShortDescription($translationShortDescription[$languageId])
                ->setLanguage($this->entityManager->getReference(Language::class, $languageId));
            $product->addTranslation($translation);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush($product);
        return $product;
    }
}
