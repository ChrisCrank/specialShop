<?php
namespace App\Model\Database\Category;
use App\Entity\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Language;
use App\Model\Database\AbstractEntityUpdate;
use App\Repository\CategoryRepository;

/**
 * @property CategoryRepository repository
 */
class CategoryUpdate extends AbstractEntityUpdate
{
    public function byParams(
        int $id,
        int $sort,
        array $translatedNames,
        array $translatedDescription,
        ?string $path = null,
        ?string $img = null,
        ? int $parentId = null,
        bool $active = false,
    ): self{
        $category = $this->repository->find($id);
        $category
            ->setSort($sort)
            ->setImg($img)
            ->setParent(($parentId) ? $this->entityManager->getReference(Category::class, $parentId) : null)
            // $parentId is 0 instead of null -.-
            ->setParentId($parentId == 0 ? null : $parentId)
            ->setActive($active)
            ->setPath($path);

        // insert translation
        foreach($translatedNames as $languageId => $value ){
            $existingTranslation = $category->getTranslation()->filter(function ($e)use($languageId){
                return $e->getLanguage()->getId() == $languageId;
            });
            if(!empty($existingTranslation->first())){
                // update
                $translation = $existingTranslation->first()
                    ->setName($value)
                    ->setDescription($translatedDescription[$languageId]);
                $this->entityManager->flush($translation);
            }else{
                // create
                $translation = (new CategoryTranslation())
                    ->setName($value)
                    ->setDescription($translatedDescription[$languageId])
                    ->setLanguage($this->entityManager->getReference(Language::class, $languageId));
            }
            $category->addTranslation($translation);
        }

        $this->entityManager->flush($category);
        return $this;
    }
}
