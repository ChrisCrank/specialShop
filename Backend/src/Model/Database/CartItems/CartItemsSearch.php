<?php
namespace App\Model\Database\CartItems;

use App\Model\Database\AbstractEntitySearch;
use App\Entity\CartItems;
use App\Repository\CartItemsRepository;

/**
 * @method CartItems|null byId(int $id)
 * @property CartItemsRepository repository
 */
class CartItemsSearch extends AbstractEntitySearch
{
    public function getByUserId(int $userId){
        $query = $this->repository
            ->createQueryBuilder('ci')
            ->innerJoin('ci.product', 'cip')
            ->where('ci.userId = :userId')
            ->setParameter('userId', $userId);
        return $query->getQuery()->getResult();
    }
}
