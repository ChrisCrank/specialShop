<?php
namespace App\Model\Database\Category;
use App\Model\Database\AbstractEntitySearch;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Criteria;

/**
 * @method Category|null byId(int $id)
 * @property CategoryRepository repository
 */
class CategorySearch extends AbstractEntitySearch
{
    public function byParentId(int $parentId){
        $query = $this->repository
            ->createQueryBuilder('c')
            ->where('parentId LIKE %|:parentId|%')
            ->setParameter('parentId', $parentId)
            ->addOrderBy('c.sort',Criteria::DESC);
        return $query->getQuery()->getResult();
    }

    public function byNameAndParent(string $name, int $languageId, ?int $parentId = null) {
        $query = $this->repository
            ->createQueryBuilder('c')
            ->innerJoin('c.translation', 'ct')
            ->where('ct.languageId = :languageId')
            ->setParameter('languageId', $languageId)
            ->andWhere('ct.name = :name')
            ->setParameter('name', $name);
        if(!empty($parentId)) {
            $query
                ->andWhere('c.parentId = :parent')
                ->setParameter('parent', $parentId);
        }
        return $query->getQuery()->getResult();
    }

    public function all(bool $fetchInactive = false){
        $query = $this->repository
            ->createQueryBuilder('c')
            ->innerJoin('c.translation', 'ct')
            ->innerJoin('ct.language', 'ctl')
            ->addOrderBy('c.sort',Criteria::DESC);
        if(!$fetchInactive)
            $query->where('c.active = 1');
        return $query->getQuery()->getResult();
    }

    public function getChildIds (int $categoryId) :array{
        $query = $this->repository
            ->createQueryBuilder('c')
            ->where('c.path LIKE :categoryId')
            ->setParameter('categoryId', '%|'.$categoryId.'|%');
        $res = $query->getQuery()->getResult();
        $ids = [];
        foreach($res as $r){
            $ids[] = $r->getId();
        }
        return $ids;
    }
}
