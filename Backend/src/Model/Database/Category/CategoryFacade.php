<?php

namespace App\Model\Database\Category;

use App\Model\Database\AbstractEntityFacade;

/**
 * @method CategorySearch search()
 * @method CategoryDelete delete()
 * @method CategoryUpdate update()
 * @method CategoryCreate create()
 */
class CategoryFacade extends AbstractEntityFacade
{
    public function getEntityName() :string
    {
        return 'category';
    }
}
