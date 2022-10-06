<?php
namespace App\Model\Database\CartItems;
use App\Model\Database\AbstractEntityDelete;
use App\Repository\CartItemsRepository;


/**
 * @property CartItemsRepository repository
 */
class CartItemsDelete extends AbstractEntityDelete
{
    public function byUserIdAndProductId(int $userId, int $id) {
        $query = $this->repository
            ->createQueryBuilder('ci')
            ->where('ci.userId = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('ci.productId = :productId')
            ->setParameter('productId', $id);
        $r = $query->getQuery()->getResult();
        if(!empty($r) && !empty($r[0])) {
            $this->entityManager->remove($r[0]);
            $this->entityManager->flush();
        }
    }

    public function byUserId(int $userId) {
        $query = $this->repository
            ->createQueryBuilder('ci')
            ->where('ci.userId = :userId')
            ->setParameter('userId', $userId);
        $cartItems = $query->getQuery()->getResult();
        foreach($cartItems as $cartItem){
            $this->entityManager->remove($cartItem);
            $this->entityManager->flush();
        }
    }
}
