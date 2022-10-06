<?php

namespace App\Model\Database\CartItems;

use App\Model\Database\AbstractEntityFacade;

/**
 * @method CartItemsSearch search()
 * @method CartItemsDelete delete()
 * @method CartItemsUpdate update()
 * @method CartItemsCreate create()
 */
class CartItemsFacade extends AbstractEntityFacade
{
    public function getEntityName() :string
    {
        return 'cartItems';
    }
}
