<?php
namespace App\Model\Database\Order;
use App\Entity\User;
use App\Model\Database\AbstractEntityCreate;
use App\Entity\Order;
use App\Repository\OrderRepository;


/**
 * @property OrderRepository repository
 */
class OrderCreate extends AbstractEntityCreate
{
    public function byParams(
        int $userId,
        float $price,
        int $vat,
        array $payload,
        string $shippingMethod,
        string $paymentMethod
    ): Order{
        /**
         * TODO: create Entities for ShippingMethod and PaymentMethod dont insert String. Well its just a demo im lazy
         */
        $order = (new Order())
            ->setUser($this->entityManager->getReference(User::class, $userId))
            ->setPrice($price)
            ->setVat($vat)
            ->setPayload($payload)
            ->setShippingMethod($shippingMethod)
            ->setPaymentMethod($paymentMethod);
        $this->entityManager->persist($order);
        $this->entityManager->flush($order);
        return $order;
    }
}
