<?php

namespace App\Controller\Api\User;

use App\Config\StaticConfigs;
use App\Controller\Api\AbstractApiAuthController;
use App\Entity\CartItems;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CartController extends AbstractApiAuthController
{
    #[Route(
        '/api/User/Cart/get',
        name: 'api_user_cart_get',
        methods: ['GET']
    )]
    /**
     * payload: [
     * ],
     * response: [
     *      'success' => <bool>,
     *      'cart' => [
     *          {
     *              'id' => <int>
     *              'quantity' => <int>,
     *              'product' => <product>,
     *              'userId' => <int>
     *          }
     *          ... array with cartItems
     *      ]
     * ]
     */
    public function index(Request $request, SerializerInterface $serializer) :Response{
        $cartItems          = $this->facade->Database()->CartItems()->search()->getByUserId($this->userId);
        $productCalculator  = $this->facade->ProductCalculator();
        /**
         * @var CartItems $item
         */
        foreach($cartItems as $item){
            $productCalculator->calculateDefaultData([$item->getProduct()], $this->lang);
        }
        $this->params['cart'] = json_decode(
            $serializer->serialize($cartItems, 'json', [
                'groups' => [
                    'cartItem',
                    'product'
                ]
            ])
        );
        $this->params['success'] = true;
        return $this->response();
    }

    #[Route(
        '/api/User/Cart/add',
        name: 'api_user_cart_add',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'productId' => <int>
     *      'quantity'  => <int>
     *      'set'       => <bool>,  if this is true the Quantity of the Item will be set not added
     * ],
     * response: [
     *      'success' => <bool>,
     * ]
     */
    public function add(Request $request) :Response{
        $quantity   = $request->get('quantity');
        $productId  = $request->get('productId');
        if(!is_numeric($quantity) || !is_numeric($productId))
            $this->params['success'] = false;
        else {
            $this->facade->Database()->CartItems()->update()->upsertItem(
                $this->userId,
                $request->get('productId'),
                $request->get('quantity'),
                (bool) $request->get('set'),
            );
            $this->params['success'] = true;
        }
        return $this->response();
    }

    #[Route(
        '/api/User/Cart/remove',
        name: 'api_user_cart_remove',
        methods: ['POST']
    )]
    /**
     * payload: [
     *      'productId' => <int>
     * ],
     * response: [
     *      'success' => <bool>,
     * ]
     */
    public function remove(Request $request) :Response{
        $this->facade->Database()->CartItems()->delete()->byUserIdAndProductId(
            $this->userId,
            $request->get('productId')
        );
        $this->params['success'] = true;
        return $this->response();
    }

    #[Route(
        '/api/User/Cart/clear',
        name: 'api_user_cart_clear',
        methods: ['DELETE']
    )]
    /**
     * payload: [
     * ],
     * response: [
     *      'success' => <bool>,
     * ]
     */
    public function clear(Request $request) :Response{
        $this->facade->Database()->CartItems()->delete()->byUserId(
            $this->userId
        );
        $this->params['success'] = true;
        return $this->response();
    }
}
