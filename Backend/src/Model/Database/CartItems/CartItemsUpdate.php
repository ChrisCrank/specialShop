<?php
namespace App\Model\Database\CartItems;
use App\Entity\CartItems;
use App\Entity\Product;
use App\Entity\User;
use App\Model\Database\AbstractEntityUpdate;
use App\Repository\CartItemsRepository;

/**
 * @property CartItemsRepository repository
 */
class CartItemsUpdate extends AbstractEntityUpdate
{
    public function upsertItem(int $userId, int $productId, int $quantity, bool $set = false){
        // check if exist
        $query = $this->repository
            ->createQueryBuilder('ci')
            ->innerJoin('ci.product', 'cip')
            ->where('ci.userId = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('cip.id = :productId')
            ->setParameter('productId', $productId);
        $res = $query->getQuery()->getResult();
        if(empty($res) || empty($res[0])){
            // create
            $cartItem = new CartItems();
            $cartItem->setProduct($this->entityManager->getReference(Product::class, $productId));
            $cartItem->setProductId($productId);
            $cartItem->setQuantity($quantity);
            $cartItem->setUserId($userId);
            $cartItem->setUser($this->entityManager->getReference(User::class, $userId));
            $this->entityManager->persist($cartItem);
        }else{
            /**
             * @var CartItems $cartItem
             */
            $cartItem = $res[0];
            if(!$set)
                $quantity = $res[0]->getQuantity() + $quantity;
            $res[0]->setQuantity($quantity);
        }
        $this->entityManager->flush($cartItem);
        return true;
    }
}
