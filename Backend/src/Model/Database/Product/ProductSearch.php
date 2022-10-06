<?php
namespace App\Model\Database\Product;
use App\Model\Database\AbstractEntitySearch;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Criteria;

/**
 * @method Product|null byId(int $id)
 * @property ProductRepository repository
 */
class ProductSearch extends AbstractEntitySearch
{
    public function byCategoryId(array $categoryIds, bool $fetchInactive = false, int $offset  = 0, $limit = 0){
        $query = $this->repository
            ->createQueryBuilder('p')
            ->innerJoin('p.translation', 'pt')
            ->innerJoin('pt.language', 'ptl')
            ->innerJoin('p.categories' ,'pc')
            ->groupBy('p');
        for($i = 0; $i < count($categoryIds); $i++){
            if($i == 0) {
                $query
                    ->where('pc.id = :categoryId'.$i)
                    ->setParameter('categoryId'.$i, $categoryIds[$i]);
            }else{
                $query
                    ->orWhere('pc.id = :categoryId'.$i)
                    ->setParameter('categoryId'.$i, $categoryIds[$i]);
            }

        }
        if($limit !== 0) {
            $query->setFirstResult($offset);
            $query->setMaxResults($limit);
        }
        $query->orderBy('p.sort',Criteria::DESC);
        if(!$fetchInactive)
            $query->andWhere('pc.active = 1');
        return $query->getQuery()->getResult();
    }

    public function all(bool $fetchInactive = false, int $offset  = 0, $limit = 0){
        $query = $this->repository
            ->createQueryBuilder('p')
            ->innerJoin('p.translation', 'pt')
            ->innerJoin('pt.language', 'ptl')
            ->addOrderBy('p.sort',Criteria::DESC)
            ->groupBy('p');;
        if($limit !== 0) {
            $query->setFirstResult($offset);
            $query->setMaxResults($limit);
        }
        if(!$fetchInactive)
            $query->where('p.active = 1');
        return $query->getQuery()->getResult();
    }
    public function byCategoryIdCount(int $categoryId, bool $fetchInactive = false) :int{
        $query = $this->entityManager->createQueryBuilder()
            ->select('count(p.id)')
            ->from(Product::class, 'p')
            ->innerJoin('p.categories' ,'pc')
            ->where('pc.id = :category')
            ->setParameter('category', $categoryId);
        if(!$fetchInactive)
            $query->andWhere('pc.active = 1');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function allCount(bool $fetchInactive = false) :int{
        $query = $this->entityManager->createQueryBuilder()
            ->select('count(p.id)')
            ->from(Product::class, 'p');
        if(!$fetchInactive)
            $query->where('pc.active = 1');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function byName(string $name, bool $fetchInactive = false) :?Product{
        $query = $this->repository
            ->createQueryBuilder('p')
            ->innerJoin('p.translation', 'pt')
            ->innerJoin('pt.language', 'ptl')
            ->leftJoin('p.categories', 'ptc')
            ->where('LOWER(pt.name) = :name')
            ->setParameter('name', strtolower($name));
        if(!$fetchInactive)
            $query->andWhere('p.active = 1');
        $r = $query->getQuery()->getResult();
        if($r)
            return $r[$query->getQuery()->getFirstResult()];
        else
            return null;
    }
    public function byIdentifier(string $id, bool $fetchInactive = false) :?Product{
        $query = $this->repository
            ->createQueryBuilder('p')
            ->innerJoin('p.translation', 'pt')
            ->innerJoin('pt.language', 'ptl')
            ->leftJoin('p.categories', 'ptc')
            ->where('p.id = :id')
            ->setParameter('id', $id);
        if(!$fetchInactive)
            $query->andWhere('p.active = 1');
        $r = $query->getQuery()->getResult();
        if($r)
            return $r[$query->getQuery()->getFirstResult()];
        else
            return null;
    }

    public function recent(int $offset, int $limit){
        $query = $this->repository
            ->createQueryBuilder('p')
            ->innerJoin('p.translation', 'pt')
            ->innerJoin('pt.language', 'ptl')
            ->leftJoin('p.categories', 'ptc')
            ->andWhere('p.active = 1')
            ->orderBy('p.createdAt', Criteria::DESC)
            ->groupBy('p');
        if($limit !== 0) {
            $query->setFirstResult($offset);
            $query->setMaxResults($limit);
        }
        return $query->getQuery()->getResult();
    }
}
