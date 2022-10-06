<?php
namespace App\Model\Database\Order;
use App\Model\Database\AbstractEntitySearch;
use App\Entity\Order;
use App\Repository\OrderRepository;


/**
 * @method Order|null byId(int $id)
 * @property OrderRepository repository
 */
class OrderSearch extends AbstractEntitySearch
{
    public function getByUserId(int $userId){
        $query = $this->repository
            ->createQueryBuilder('o')
            ->where('o.userId = :userId')
            ->setParameter('userId', $userId);
        return $query->getQuery()->getResult();
    }
}
