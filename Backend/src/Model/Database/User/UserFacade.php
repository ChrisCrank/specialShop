<?php

namespace App\Model\Database\User;

use App\Model\Database\AbstractEntityFacade;

/**
 * @method UserSearch search()
 * @method UserDelete delete()
 * @method UserUpdate update()
 * @method UserCreate create()
 */
class UserFacade extends AbstractEntityFacade
{
    public function getEntityName() :string
    {
        return 'user';
    }
}
