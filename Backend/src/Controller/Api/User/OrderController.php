<?php

namespace App\Controller\Api\User;

use App\Config\StaticConfigs;
use App\Controller\Api\AbstractApiAuthController;
use App\Entity\CartItems;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class OrderController extends AbstractApiAuthController
{
    #[Route(
        '/api/User/Order/get',
        name: 'api_user_order_get',
        methods: ['GET']
    )]
    /**
     * payload: [
     * ],
     * response: [
     *      'success'   => <bool>,
     *      'orders'    => [    <array>
     *          {   <object>
     *              'id'        => <int>
     *              'price'     => <int>,
     *              'tax'       => <product>,
     *              'payload'   => <object>
     *          }
     *      ]
     * ]
     */
    public function index(Request $request, SerializerInterface $serializer) :Response{
        $orders = $this->facade->Database()->Order()->search()->getByUserId($this->userId);
        $this->params['orders']     = json_decode($serializer->serialize($orders, 'json', [
            'groups' => [
                'order'
            ]
        ]));
        $this->params['success']    = true;
        return $this->response();
    }

    #[Route(
        '/api/User/Order/add',
        name: 'api_user_order_add',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'products'  =>      <array> products to order
     *      [
     *          {
     *              'productId' =>  <int>,  productId
     *              'quantity'  =>  <int>,  quantity
     *          },
     *          ...
     *      ]
     *      'shippingMethod'  => <int>,  possibilities e (expres),d (default)
     *      'PaymentMethod'   => <int>   possibniliteis p (Paypal), i (Invoice), c (Cash on Delivery)
     * ],
     * response: [
     *      'success' =>            <bool>
     *      'codes' => [            <array>
     *          "F1" => ShippingMethod not valid
     *          "F2" => PaymentMethod not valid,
     *          "F3" => Products not valid,
     *          "F4" => Quantity higher then available stock,
     *      ],
     *      'productIds' => []      <array>     productIds of Produts which are not in stock for the requested quantity
     * ]
     */
    public function add(Request $request, SerializerInterface $serializer) :Response{
        $success            = false;
        $codes              = [];
        $productIds         = [];
        $products           = $request->get('products');
        $shippingMethod     = $request->get('shippingMethod');
        $paymentMethod      = $request->get('paymentMethod');

        if(!in_array($shippingMethod,['e','d']))
            $codes[] = "F1";
        if(!in_array($paymentMethod,['p','i', 'c']))
            $codes[] = "F2";
        if(!is_array($products))
            $codes[] = 'F3';
        else {
            $totalPrice = 0;
            $payload            = ['products' => []];
            $productCalculator  = $this->facade->ProductCalculator();
            foreach ($products as $product) {
                $productEntity = $this->facade->Database()->Product()->search()->byId($product['productId']);
                if (!isset($product['quantity']) || !is_numeric($product['quantity']))
                    $codes[] = 'F3';
                if (!isset($product['productId']) || empty($productEntity)) {
                    $codes[] = 'F3';
                    continue;
                }
                if ($product['quantity'] > $productEntity->getStock()) {
                    $codes[]        = "F4";
                    $productIds[]   = $product['productId'];
                    continue;
                }

                $totalPrice             += $productEntity->getPrice() * $product['quantity'];
                $productCalculator->calculateDefaultData([$productEntity], $this->lang);
                $productPayload = json_decode(
                    $serializer->serialize($productEntity, 'json', [
                        'groups' => [
                            'product',
                            'product_translation',
                            'language'
                        ]
                    ]), true
                );
                $productPayload['quantity'] = $product['quantity'];
                $payload['products'][]  = $productPayload;
            }
        }

        if(empty($codes)){
            $this->facade->Database()->Order()->create()->byParams(
                $this->userId,
                $totalPrice,
                19,
                $payload,
                $shippingMethod,
                $paymentMethod
            );

            // update Product Stock
            foreach ($products as $product) {
                $this->facade->Database()->Product()->update()->stock($product['productId'], $product['quantity'], true);
            }

            $success = true;
        }
        $this->params['success']    = $success;
        $this->params['productIds'] = $productIds;
        return $this->response();
    }
}
