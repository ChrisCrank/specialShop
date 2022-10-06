<?php
namespace App\Model\Database\Product;
use App\Entity\Category;
use App\Entity\Language;
use App\Entity\ProductTranslation;
use App\Model\Database\AbstractEntityUpdate;
use App\Repository\ProductRepository;

/**
 * @property ProductRepository repository
 */
class ProductUpdate extends AbstractEntityUpdate
{
    public function stock(int $productId, int $stock, bool $calculate = false) :self{
        $product = $this->repository->find($productId);
        if($calculate)
            $product->setStock($product->getStock()-$stock);
        else
            $product->setStock($stock);
        $this->entityManager->flush($product);
        return $this;
    }

    public function byParams(
        int $id,
        int $sort,
        int $stock,
        float $price,
        string $productNumber,
        array $translatedNames,
        array $translatedDescription,
        array $translatedShortDescription,
        string $img,
        ?array $categoryIds = [],
        bool $active = false,
    ): self{
        $product = $this->repository->find($id);
        $product
            ->setSort($sort)
            ->setStock($stock)
            ->setPrice($price)
            ->setProductNumber($productNumber)
            ->setImg($img)
            ->setActive($active);

        // remove categories
        foreach($product->getCategoryIds() as $categoryId){
            if(!in_array($categoryId, $categoryIds))
                $product->removeCategory($this->entityManager->getReference(Category::class, $categoryId));
        }
        // add Categories if not already exist
        foreach($categoryIds as $categoryId){
            if(!in_array($categoryId, $product->getCategoryIds())){
                $product->addCategory(
                    $this->entityManager->getReference(Category::class, $categoryId)
                );
            }
        }
        // insert translation
        foreach($translatedNames as $languageId => $value ){
            $existingTranslation = $product->getTranslation()->filter(function ($e)use($languageId){
                return $e->getLanguage()->getId() == $languageId;
            });
            if(!empty($existingTranslation->first())){
                // update
                $translation = $existingTranslation->first()
                    ->setName($value)
                    ->setDescription($translatedDescription[$languageId])
                    ->setShortDescription($translatedShortDescription[$languageId]);
                $this->entityManager->flush($translation);
            }else{
                // create
                $translation = (new ProductTranslation())
                    ->setName($value)
                    ->setDescription($translatedDescription[$languageId])
                    ->setShortDescription($translatedShortDescription[$languageId])
                    ->setLanguage($this->entityManager->getReference(Language::class, $languageId));
            }
            $product->addTranslation($translation);
        }

        $this->entityManager->flush($product);
        return $this;
    }
}
