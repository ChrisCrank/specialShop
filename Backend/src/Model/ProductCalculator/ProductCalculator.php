<?php
namespace App\Model\ProductCalculator;
use App\Config\StaticConfigs;
use App\Entity\Product;
use App\Model\Image\Image;

class ProductCalculator
{
    private Image $image;

    public function __construct(Image $image){
        $this->image = $image;
    }

    public function calculateDefaultData(array $products, string $lang) :array{
        /**
         * @var Product $category
         */
        foreach($products as $product){
            // set translations
            foreach($product->getTranslation() as $translation){
                if($translation->getLanguage()->getIso() == $lang) {
                    $product->setName($translation->getName());
                    $product->setDescription($translation->getDescription());
                    $product->setShortDescription($translation->getShortDescription());
                    break;
                }
                if($translation->getLanguage()->getIso() == StaticConfigs::DEFAULT_STORE_LANG){
                    $product->setName($translation->getName());
                    $product->setDescription($translation->getDescription());
                    $product->setShortDescription($translation->getShortDescription());
                }
            }

            // load image information
            $product->setCalculatedImages($this->image->loadImages($product->getImg()));
        }
        return $products;
    }
}
