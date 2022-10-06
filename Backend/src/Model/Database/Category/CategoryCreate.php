<?php
namespace App\Model\Database\Category;
use App\Entity\CategoryTranslation;
use App\Entity\Language;
use App\Model\Database\AbstractEntityCreate;
use App\Entity\Category;
use App\Repository\CategoryRepository;


/**
 * @property CategoryRepository repository
 */
class CategoryCreate extends AbstractEntityCreate
{
    /**
     * @param int $sort
     * @param array $translatedNames -> [<languageId> => $value, ...]
     * @param array $translatedDescription -> [<languageId> => $value, ...]
     * @param string|null $path
     * @param string|null $img
     * @param int|null $parentId
     * @param bool $active
     * @return Category
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * NOTE the Keys of $translatedNames should always also exist in $translatedDescription
     */
    public function byParams(
        int $sort,
        array $translatedNames,
        array $translatedDescription,
        ?string $path = null,
        ?string $img = null,
        ? int $parentId = null,
        bool $active = false,
    ): Category{
        $category = (new Category())
            ->setSort($sort)
            ->setImg($img)
            ->setParent(($parentId) ? $this->entityManager->getReference(Category::class, $parentId) : null)
            ->setParentId($parentId)
            ->setActive($active)
            ->setPath($path);

        // insert translation
        foreach($translatedNames as $languageId => $value ){
            $translation = (new CategoryTranslation())
                ->setName($value)
                ->setDescription($translatedDescription[$languageId])
                /**
                 * TODO: find a way so Doctrine dosent have to load a whole Language Object in order to insert a fucking forain key
                 */
                // ->setLanguageId($languageId); // <-- does not work because ORM is stupid ?? (or I am)
                ->setLanguage($this->entityManager->getReference(Language::class, $languageId));
            $category->addTranslation($translation);
        }

        $this->entityManager->persist($category);
        $this->entityManager->flush($category);
        return $category;
    }
}
