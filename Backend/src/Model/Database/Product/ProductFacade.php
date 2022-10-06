<?php

namespace App\Model\Database\Product;

use App\Model\Database\AbstractEntityFacade;

/**
 * @method ProductSearch search()
 * @method ProductDelete delete()
 * @method ProductUpdate update()
 * @method ProductCreate create()
 */
class ProductFacade extends AbstractEntityFacade
{
    public function getEntityName() :string
    {
        return 'product';
    }
}
