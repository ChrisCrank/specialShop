<?php

namespace App\Model\Database\Order;

use App\Model\Database\AbstractEntityFacade;

/**
 * @method OrderSearch search()
 * @method OrderDelete delete()
 * @method OrderUpdate update()
 * @method OrderCreate create()
 */
class OrderFacade extends AbstractEntityFacade
{
    public function getEntityName() :string
    {
        return 'order';
    }
}
